import { useEffect, useState, useCallback } from 'react'
import type { Session } from '@supabase/supabase-js'
import { Loader2, LogOut, RefreshCw, Inbox } from 'lucide-react'
import { Button } from '@/components/ui/button'
import { Card, CardContent } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Badge } from '@/components/ui/badge'
import {
  supabase,
  isSupabaseConfigured,
  type ProgramApplication,
  type JobApplication,
} from '@/lib/supabase'

type Tab = 'program' | 'job' | 'messages'

interface ContactMessage {
  id: string
  name: string
  email: string
  subject: string
  message: string
  created_at: string
}

const STATUSES = ['new', 'contacted', 'accepted', 'declined'] as const

const formatDate = (iso: string) =>
  new Date(iso).toLocaleString(undefined, {
    year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit',
  })

function LoginForm() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [busy, setBusy] = useState(false)

  const submit = async (e: React.FormEvent) => {
    e.preventDefault()
    setBusy(true)
    setError('')
    const { error: signInError } = await supabase.auth.signInWithPassword({ email, password })
    setBusy(false)
    // Deliberately vague: a precise message would tell an attacker which
    // half of the credentials was wrong.
    if (signInError) setError('Could not sign in. Check the email and password and try again.')
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-muted/30 px-4">
      <Card className="w-full max-w-sm border-border">
        <CardContent className="p-6 sm:p-8">
          <h1 className="text-xl font-semibold text-foreground mb-1">BE WELL Staff</h1>
          <p className="text-sm text-muted-foreground mb-6">Sign in to view applications and messages.</p>
          <form onSubmit={submit} className="space-y-4">
            <div className="space-y-1.5">
              <Label htmlFor="admin-email">Email</Label>
              <Input id="admin-email" type="email" required autoComplete="username"
                value={email} onChange={(e) => setEmail(e.target.value)} />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="admin-password">Password</Label>
              <Input id="admin-password" type="password" required autoComplete="current-password"
                value={password} onChange={(e) => setPassword(e.target.value)} />
            </div>
            {error && <p className="text-sm text-destructive" role="alert">{error}</p>}
            <Button type="submit" className="w-full" disabled={busy}>
              {busy ? <Loader2 className="w-4 h-4 animate-spin" /> : 'Sign in'}
            </Button>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}

function EmptyState({ label }: { label: string }) {
  return (
    <div className="text-center py-16 text-muted-foreground">
      <Inbox className="w-10 h-10 mx-auto mb-3 opacity-40" />
      <p>No {label} yet.</p>
    </div>
  )
}

export function Admin() {
  const [session, setSession] = useState<Session | null>(null)
  const [checking, setChecking] = useState(true)
  const [isStaff, setIsStaff] = useState<boolean | null>(null)
  const [tab, setTab] = useState<Tab>('program')
  const [loading, setLoading] = useState(false)
  const [programApps, setProgramApps] = useState<ProgramApplication[]>([])
  const [jobApps, setJobApps] = useState<JobApplication[]>([])
  const [messages, setMessages] = useState<ContactMessage[]>([])

  useEffect(() => {
    document.title = 'Staff - BE WELL'
  }, [])

  useEffect(() => {
    supabase.auth.getSession().then(({ data }) => {
      setSession(data.session)
      setChecking(false)
    })
    const { data: sub } = supabase.auth.onAuthStateChange((_event, s) => {
      setSession(s)
      if (!s) setIsStaff(null)
    })
    return () => sub.subscription.unsubscribe()
  }, [])

  const load = useCallback(async () => {
    if (!session) return
    setLoading(true)

    // A logged-in account is not automatically staff — the allowlist decides.
    const { data: staffRow } = await supabase.from('staff').select('user_id').maybeSingle()
    setIsStaff(Boolean(staffRow))

    if (staffRow) {
      const [p, j, m] = await Promise.all([
        supabase.from('program_applications').select('*').order('created_at', { ascending: false }),
        supabase.from('job_applications').select('*').order('created_at', { ascending: false }),
        supabase.from('contact_messages').select('*').order('created_at', { ascending: false }),
      ])
      setProgramApps(p.data ?? [])
      setJobApps(j.data ?? [])
      setMessages((m.data as ContactMessage[]) ?? [])
    }
    setLoading(false)
  }, [session])

  useEffect(() => { void load() }, [load])

  const setStatus = async (table: 'program_applications' | 'job_applications', id: string, status: string) => {
    const { error } = await supabase.from(table).update({ status }).eq('id', id)
    if (error) {
      console.error('Failed to update status', error)
      return
    }
    if (table === 'program_applications') {
      setProgramApps((rows) => rows.map((r) => (r.id === id ? { ...r, status } : r)))
    } else {
      setJobApps((rows) => rows.map((r) => (r.id === id ? { ...r, status } : r)))
    }
  }

  if (!isSupabaseConfigured) {
    return <div className="min-h-screen flex items-center justify-center p-8 text-center text-muted-foreground">
      This site was built without its database configuration, so the staff area is unavailable.
    </div>
  }

  if (checking) {
    return <div className="min-h-screen flex items-center justify-center"><Loader2 className="w-6 h-6 animate-spin text-muted-foreground" /></div>
  }

  if (!session) return <LoginForm />

  if (isStaff === false) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center gap-4 p-8 text-center">
        <p className="text-muted-foreground max-w-sm">
          This account does not have staff access. Ask an administrator to add you.
        </p>
        <Button variant="outline" onClick={() => supabase.auth.signOut()}>Sign out</Button>
      </div>
    )
  }

  const tabs: { key: Tab; label: string; count: number }[] = [
    { key: 'program', label: 'Program Applications', count: programApps.length },
    { key: 'job', label: 'Job Applications', count: jobApps.length },
    { key: 'messages', label: 'Messages', count: messages.length },
  ]

  return (
    <div className="min-h-screen bg-muted/30">
      <header className="bg-background border-b border-border">
        <div className="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between gap-4">
          <div>
            <h1 className="font-semibold text-foreground">BE WELL Staff</h1>
            <p className="text-xs text-muted-foreground">{session.user.email}</p>
          </div>
          <div className="flex items-center gap-2">
            <Button variant="outline" size="sm" onClick={() => void load()} disabled={loading}>
              <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
              <span className="hidden sm:inline ml-2">Refresh</span>
            </Button>
            <Button variant="outline" size="sm" onClick={() => supabase.auth.signOut()}>
              <LogOut className="w-4 h-4" />
              <span className="hidden sm:inline ml-2">Sign out</span>
            </Button>
          </div>
        </div>
      </header>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 py-6">
        <div className="flex gap-2 mb-6 flex-wrap">
          {tabs.map((t) => (
            <Button key={t.key} variant={tab === t.key ? 'default' : 'outline'} size="sm" onClick={() => setTab(t.key)}>
              {t.label}
              <Badge variant="secondary" className="ml-2">{t.count}</Badge>
            </Button>
          ))}
        </div>

        {loading && <div className="py-16 text-center"><Loader2 className="w-6 h-6 animate-spin mx-auto text-muted-foreground" /></div>}

        {!loading && tab === 'program' && (
          programApps.length === 0 ? <EmptyState label="program applications" /> : (
            <div className="space-y-3">
              {programApps.map((a) => (
                <Card key={a.id} className="border-border">
                  <CardContent className="p-4 sm:p-5">
                    <div className="flex flex-wrap items-start justify-between gap-3 mb-2">
                      <div>
                        <p className="font-medium text-foreground">{a.name}</p>
                        <p className="text-sm text-muted-foreground">
                          <a className="hover:underline" href={`tel:${a.phone}`}>{a.phone}</a>
                          {a.email && <> · <a className="hover:underline" href={`mailto:${a.email}`}>{a.email}</a></>}
                        </p>
                      </div>
                      <div className="flex items-center gap-2">
                        <span className="text-xs text-muted-foreground">{formatDate(a.created_at)}</span>
                        <select
                          className="text-sm border border-input rounded-md px-2 py-1 bg-background"
                          value={a.status}
                          onChange={(e) => void setStatus('program_applications', a.id, e.target.value)}
                        >
                          {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
                        </select>
                      </div>
                    </div>
                    <div className="text-sm text-foreground/90 space-y-1">
                      <p><span className="text-muted-foreground">Condition:</span> {a.condition || '—'}</p>
                      <p><span className="text-muted-foreground">Programme:</span> {a.program_length || '—'}</p>
                      {a.message && <p className="pt-1 whitespace-pre-wrap">{a.message}</p>}
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          )
        )}

        {!loading && tab === 'job' && (
          jobApps.length === 0 ? <EmptyState label="job applications" /> : (
            <div className="space-y-3">
              {jobApps.map((a) => (
                <Card key={a.id} className="border-border">
                  <CardContent className="p-4 sm:p-5">
                    <div className="flex flex-wrap items-start justify-between gap-3 mb-2">
                      <div>
                        <p className="font-medium text-foreground">{a.name}</p>
                        <p className="text-sm text-muted-foreground">
                          <a className="hover:underline" href={`tel:${a.phone}`}>{a.phone}</a>
                          {a.email && <> · <a className="hover:underline" href={`mailto:${a.email}`}>{a.email}</a></>}
                        </p>
                      </div>
                      <div className="flex items-center gap-2">
                        <span className="text-xs text-muted-foreground">{formatDate(a.created_at)}</span>
                        <select
                          className="text-sm border border-input rounded-md px-2 py-1 bg-background"
                          value={a.status}
                          onChange={(e) => void setStatus('job_applications', a.id, e.target.value)}
                        >
                          {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
                        </select>
                      </div>
                    </div>
                    <div className="text-sm text-foreground/90 space-y-1">
                      <p><span className="text-muted-foreground">Position:</span> {a.position || '—'}</p>
                      {a.experience && <p><span className="text-muted-foreground">Experience:</span> {a.experience}</p>}
                      {a.motivation && <p className="pt-1 whitespace-pre-wrap">{a.motivation}</p>}
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          )
        )}

        {!loading && tab === 'messages' && (
          messages.length === 0 ? <EmptyState label="messages" /> : (
            <div className="space-y-3">
              {messages.map((m) => (
                <Card key={m.id} className="border-border">
                  <CardContent className="p-4 sm:p-5">
                    <div className="flex flex-wrap items-start justify-between gap-3 mb-2">
                      <div>
                        <p className="font-medium text-foreground">{m.subject}</p>
                        <p className="text-sm text-muted-foreground">
                          {m.name} · <a className="hover:underline" href={`mailto:${m.email}`}>{m.email}</a>
                        </p>
                      </div>
                      <span className="text-xs text-muted-foreground">{formatDate(m.created_at)}</span>
                    </div>
                    <p className="text-sm text-foreground/90 whitespace-pre-wrap">{m.message}</p>
                  </CardContent>
                </Card>
              ))}
            </div>
          )
        )}
      </div>
    </div>
  )
}

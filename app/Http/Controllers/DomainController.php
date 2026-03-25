<?php

namespace App\Http\Controllers;

use App\Enums\CheckHttpMethod;
use App\Enums\NotifyChannel;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DomainController extends Controller
{
    public function index(): Response
    {
        $domains = Auth::user()->domains()
            ->withCount('checks')
            ->with('lastCheck')
            ->latest()
            ->paginate(15);

        return Inertia::render('Domains/Index', [
            'domains' => $domains,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Domains/Create', [
            'methods' => CheckHttpMethod::options(),
            'notifyOptions' => NotifyChannel::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'url', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'check_interval' => ['required', 'integer', 'min:1', 'max:1440'],
            'timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'http_method' => ['required', 'in:GET,HEAD'],
            'expected_content' => ['nullable', 'string', 'max:1000'],
            'notify_channel' => ['required', 'in:email,telegram,both,none'],
            'is_active' => ['boolean'],
            'history_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        Auth::user()->domains()->create($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Domain created successfully.');
    }

    public function show(Domain $domain): Response
    {
        $this->authorize('view', $domain);

        $domain->load(['checks' => fn($q) => $q->latest()->limit(50)]);

        return Inertia::render('Domains/Show', [
            'domain' => $domain,
        ]);
    }

    public function edit(Domain $domain): Response
    {
        $this->authorize('update', $domain);

        return Inertia::render('Domains/Edit', [
            'domain' => $domain,
            'methods' => CheckHttpMethod::options(),
            'notifyOptions' => NotifyChannel::options(),
        ]);
    }

    public function update(Request $request, Domain $domain): RedirectResponse
    {
        $this->authorize('update', $domain);

        $validated = $request->validate([
            'url' => ['required', 'url', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'check_interval' => ['required', 'integer', 'min:1', 'max:1440'],
            'timeout' => ['required', 'integer', 'min:1', 'max:60'],
            'http_method' => ['required', 'in:GET,HEAD'],
            'expected_content' => ['nullable', 'string', 'max:1000'],
            'notify_channel' => ['required', 'in:email,telegram,both,none'],
            'is_active' => ['boolean'],
            'history_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $domain->update($validated);

        return redirect()->route('domains.index')
            ->with('success', 'Domain updated successfully.');
    }

    public function destroy(Domain $domain): RedirectResponse
    {
        $this->authorize('delete', $domain);

        $domain->delete();

        return redirect()->route('domains.index')
            ->with('success', 'Domain deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Enums\CheckHttpMethod;
use App\Enums\NotifyChannel;
use App\Http\Requests\StoreDomainRequest;
use App\Http\Requests\UpdateDomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
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

    public function store(StoreDomainRequest $request): RedirectResponse
    {
        Auth::user()->domains()->create($request->validated());

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

    public function update(UpdateDomainRequest $request, Domain $domain): RedirectResponse
    {
        $domain->update($request->validated());

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

@php
    $user = auth()->user();
    $canDownload = $user->canDownload();
    $hasPlanAccess = $user->hasPlanAccess();
    $billingEnabled = $user->billingEnabled();
    $compact = $compact ?? false;
    $showAts = $showAts ?? false;
    $showDuplicate = $showDuplicate ?? false;
    $showDelete = $showDelete ?? false;

    $previewClass = $compact ? 'admin-badge bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700' : 'admin-btn-secondary';
    $editClass = $compact ? 'admin-badge bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-500/15 dark:text-indigo-300 dark:hover:bg-indigo-500/25' : 'admin-btn-primary';
    $downloadClass = $compact ? 'admin-badge bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-500/15 dark:text-emerald-300 dark:hover:bg-emerald-500/25' : 'admin-btn-secondary';
    $lockedClass = $compact ? 'admin-badge cursor-not-allowed bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500' : 'admin-btn-secondary cursor-not-allowed opacity-60';
@endphp

<div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('resumes.show', $resume) }}" class="{{ $previewClass }}">Preview</a>
    <a href="{{ route('resumes.edit', $resume) }}" class="{{ $editClass }}">Edit</a>

    @if ($canDownload && $hasPlanAccess)
        <a href="{{ route('resumes.pdf', $resume) }}" class="{{ $downloadClass }}">Download</a>
    @elseif ($billingEnabled && ! $hasPlanAccess)
        <a href="{{ route('plans.index') }}"
           class="{{ $compact ? 'admin-badge bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-500/15 dark:text-amber-300' : 'admin-btn-secondary border-amber-200 text-amber-700 hover:bg-amber-50 dark:border-amber-500/30 dark:text-amber-300 dark:hover:bg-amber-500/10' }}"
           title="Subscribe to a plan to download PDFs">
            Download
        </a>
    @elseif ($billingEnabled && ! $canDownload)
        <span class="{{ $lockedClass }}"
              title="You have used all PDF downloads for your plan this period. Upgrade for more.">
            Download
        </span>
    @else
        <a href="{{ route('resumes.pdf', $resume) }}" class="{{ $downloadClass }}">Download</a>
    @endif

    @if ($showAts)
        <a href="{{ route('ats.create', ['resume' => $resume->id]) }}" class="admin-badge bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-500/15 dark:text-blue-300 dark:hover:bg-blue-500/25">ATS</a>
    @endif

    @if ($showDuplicate)
        <form method="POST" action="{{ route('resumes.duplicate', $resume) }}">
            @csrf
            <button type="submit" class="admin-btn-secondary">Duplicate</button>
        </form>
    @endif

    @if ($showDelete)
        <form method="POST" action="{{ route('resumes.destroy', $resume) }}" onsubmit="return confirm('Delete this resume?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="admin-btn-danger">Delete</button>
        </form>
    @endif
</div>

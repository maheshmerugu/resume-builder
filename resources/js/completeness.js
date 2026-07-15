export const COMPLETENESS_SECTIONS = [
    { key: 'name', label: 'Name', hint: 'Add your full name in Contact details.', section: 'section-contact', test: (r) => filled(r.full_name) },
    { key: 'email', label: 'Email', hint: 'Add a professional email address.', section: 'section-contact', test: (r) => filled(r.email) },
    { key: 'summary', label: 'Summary', hint: 'Write a short professional summary.', section: 'section-summary', test: (r) => filled(r.summary) },
    { key: 'experience', label: 'Experience', hint: 'Add at least one role with company name.', section: 'section-experience', test: (r) => hasContent(r.experience, ['role', 'company']) },
    { key: 'education', label: 'Education', hint: 'Add your degree and school.', section: 'section-education', test: (r) => hasContent(r.education, ['degree', 'school']) },
    { key: 'skills', label: 'Skills', hint: 'List your top technical or professional skills.', section: 'section-skills', test: (r) => listItems(r.skills_raw).length > 0 },
];

function filled(value) {
    return Boolean(value && String(value).trim());
}

function hasContent(arr, fields) {
    return Array.isArray(arr) && arr.some((item) => fields.some((f) => item[f] && String(item[f]).trim()));
}

function listItems(t) {
    return t ? t.split(/[,\n]+/).map((s) => s.trim()).filter(Boolean) : [];
}

export function computeCompleteness(resume) {
    const checks = COMPLETENESS_SECTIONS.map(({ key, label, hint, section, test }) => ({
        key,
        label,
        hint,
        section,
        done: test(resume),
    }));

    const done = checks.filter((check) => check.done).length;
    const percent = Math.round((done / checks.length) * 100);
    const next = checks.find((check) => ! check.done) || null;

    return { percent, checks, next, label: labelFor(percent), status: statusFor(percent), tier: tierFor(percent) };
}

export function labelFor(percent) {
    if (percent >= 100) return 'Your resume is ready to save and download.';
    if (percent >= 67) return 'Almost there — fill in the remaining sections.';
    if (percent >= 34) return 'Good progress — keep going.';
    return 'Just getting started — complete the key sections below.';
}

export function statusFor(percent) {
    if (percent >= 100) return 'Complete';
    if (percent >= 67) return 'Almost done';
    if (percent >= 34) return 'In progress';
    return 'Getting started';
}

export function tierFor(percent) {
    if (percent >= 100) return 'complete';
    if (percent >= 67) return 'high';
    if (percent >= 34) return 'mid';
    return 'low';
}

export function barClass(percent) {
    const tier = tierFor(percent);
    if (tier === 'complete') return 'bg-gradient-to-r from-emerald-500 to-teal-500';
    if (tier === 'high') return 'bg-gradient-to-r from-indigo-500 to-violet-500';
    if (tier === 'mid') return 'bg-gradient-to-r from-blue-500 to-cyan-500';
    return 'bg-gradient-to-r from-amber-400 to-orange-500';
}

export function ringClass(percent) {
    const tier = tierFor(percent);
    if (tier === 'complete') return 'stroke-emerald-500';
    if (tier === 'high') return 'stroke-indigo-500';
    if (tier === 'mid') return 'stroke-blue-500';
    return 'stroke-amber-500';
}

export function badgeClass(percent) {
    const tier = tierFor(percent);
    if (tier === 'complete') return 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300';
    if (tier === 'high') return 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300';
    if (tier === 'mid') return 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300';
    return 'bg-amber-50 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300';
}

export function textClass(percent) {
    const tier = tierFor(percent);
    if (tier === 'complete') return 'text-emerald-600 dark:text-emerald-400';
    if (tier === 'high') return 'text-indigo-600 dark:text-indigo-400';
    if (tier === 'mid') return 'text-blue-600 dark:text-blue-400';
    return 'text-amber-600 dark:text-amber-400';
}

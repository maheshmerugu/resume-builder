import {
    barClass,
    badgeClass,
    computeCompleteness,
    labelFor,
    ringClass,
    statusFor,
    textClass,
} from './completeness';

export default function resumeForm(init, themeCatalog, aiEndpoint) {
    return {
        resume: init,
        themeCatalog: themeCatalog || {},
        themeOpen: false,
        aiLoading: null,
        aiError: '',
        aiNotice: '',
        aiEndpoint: aiEndpoint || '',
        progressDockVisible: false,

        init() {
            const hash = window.location.hash?.replace('#', '');
            if (hash && hash.startsWith('section-')) {
                this.$nextTick(() => this.scrollToSection(hash));
            }

            this.$nextTick(() => {
                const panel = document.getElementById('completeness-full');
                if (! panel || typeof IntersectionObserver === 'undefined') {
                    return;
                }

                const observer = new IntersectionObserver(
                    ([entry]) => {
                        this.progressDockVisible = ! entry.isIntersecting;
                    },
                    { threshold: 0, rootMargin: '-4.5rem 0px 0px 0px' }
                );

                observer.observe(panel);
            });
        },

        currentTheme() {
            return this.themeCatalog[this.resume.template] || Object.values(this.themeCatalog)[0] || {};
        },
        themeLabel() {
            return (this.currentTheme().label || this.resume.template) + ' theme';
        },
        previewWrap(theme) {
            const light = theme?.colors?.light || '#eef2ff';
            return { background: `linear-gradient(135deg, ${light}, #fff)` };
        },
        previewHeader(theme) {
            const c = theme?.colors || {};
            const styles = {
                modern: { borderLeft: `4px solid ${c.primary}`, paddingLeft: '8px' },
                classic: { borderBottom: `2px solid ${c.text}`, textAlign: 'center', paddingBottom: '6px' },
                minimal: {},
                banner: { background: c.primary, padding: '8px', borderRadius: '4px', marginBottom: '8px' },
                underline: { borderBottom: `3px solid ${c.primary}`, paddingBottom: '4px' },
                boxed: { border: `2px solid ${c.primary}`, background: c.light, padding: '8px', borderRadius: '4px' },
            };
            return styles[theme?.layout] || styles.modern;
        },
        themeHeaderStyle() {
            const t = this.currentTheme();
            const c = t.colors || {};
            const styles = {
                modern: { borderLeft: `4px solid ${c.primary}`, paddingLeft: '12px', marginBottom: '12px' },
                classic: { textAlign: 'center', borderBottom: `2px solid ${c.text}`, paddingBottom: '8px', marginBottom: '12px' },
                minimal: { borderBottom: `1px solid ${c.border}`, paddingBottom: '10px', marginBottom: '12px' },
                banner: { background: c.primary, color: '#fff', padding: '14px', borderRadius: '4px', marginBottom: '14px' },
                underline: { borderBottom: `4px solid ${c.primary}`, paddingBottom: '6px', marginBottom: '14px' },
                boxed: { border: `2px solid ${c.primary}`, background: c.light, padding: '12px', borderRadius: '4px', marginBottom: '14px' },
            };
            return styles[t.layout] || styles.modern;
        },
        headerTextColor() {
            const t = this.currentTheme();
            return t.layout === 'banner' ? '#ffffff' : (t.colors?.text || '#111827');
        },
        headerAccentColor() {
            const t = this.currentTheme();
            return t.layout === 'banner' ? '#ffffff' : (t.colors?.primary || '#4f46e5');
        },
        headerMutedColor() {
            const t = this.currentTheme();
            return t.layout === 'banner' ? 'rgba(255,255,255,0.85)' : (t.colors?.muted || '#6b7280');
        },
        sectionHeadingStyle() {
            const c = this.currentTheme().colors || {};
            return { color: c.primary, borderColor: c.border };
        },
        skillBadgeStyle() {
            const c = this.currentTheme().colors || {};
            return { background: c.light, color: c.primary_dark || c.primary };
        },
        wordCount(t) { return t ? t.trim().split(/\s+/).filter(Boolean).length : 0; },
        lines(t) { return t ? t.split(/\n+/).map(s => s.trim()).filter(Boolean) : []; },
        listItems(t) { return t ? t.split(/[,\n]+/).map(s => s.trim()).filter(Boolean) : []; },
        contactLine() {
            return [this.resume.email, this.resume.phone, this.resume.location, this.resume.linkedin, this.resume.website]
                .filter(Boolean).join('  |  ');
        },
        hasContent(arr, fields) {
            return Array.isArray(arr) && arr.some(item => fields.some(f => item[f] && String(item[f]).trim()));
        },
        filled(value) {
            return Boolean(value && String(value).trim());
        },
        completenessSnapshot() {
            return computeCompleteness(this.resume);
        },
        completenessChecks() {
            return this.completenessSnapshot().checks;
        },
        completenessPercent() {
            return this.completenessSnapshot().percent;
        },
        completenessLabel() {
            return labelFor(this.completenessPercent());
        },
        completenessStatusBadge() {
            return statusFor(this.completenessPercent());
        },
        completenessBarClass() {
            return barClass(this.completenessPercent());
        },
        completenessRingClass() {
            return ringClass(this.completenessPercent());
        },
        completenessBadgeClass() {
            return badgeClass(this.completenessPercent());
        },
        completenessTextClass() {
            return textClass(this.completenessPercent());
        },
        nextStep() {
            return this.completenessSnapshot().next;
        },
        isNextSection(sectionId) {
            return this.nextStep()?.section === sectionId;
        },
        scrollToSection(sectionId) {
            const el = document.getElementById(sectionId);
            if (! el) {
                return;
            }

            const offset = this.progressDockVisible ? 132 : 88;
            const top = el.getBoundingClientRect().top + window.scrollY - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        },
        scrollToNextStep() {
            const next = this.nextStep();
            if (next?.section) {
                this.scrollToSection(next.section);
            }
        },
        scrollToCompletenessPanel() {
            document.getElementById('completeness-full')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        },
        addExperience() { this.resume.experience.push({ role: '', company: '', location: '', start: '', end: '', bullets: '' }); },
        addEducation() { this.resume.education.push({ degree: '', school: '', field: '', start: '', end: '' }); },
        addProject() { this.resume.projects.push({ name: '', tech: '', description: '' }); },
        addCertification() { this.resume.certifications.push({ name: '', issuer: '', year: '' }); },
        removeRow(section, i) { this.resume[section].splice(i, 1); },

        async aiWrite(target) {
            this.aiError = '';
            this.aiNotice = '';
            this.aiLoading = target;

            const parsed = this.parseAiTarget(target);
            const context = { ...this.resume, index: parsed.index };

            try {
                const response = await fetch(this.aiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        field: parsed.field,
                        index: parsed.index,
                        context,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'AI writing failed. Please try again.');
                }

                this.applyAiContent(parsed.field, data.content, parsed.index);
                this.aiNotice = data.ai_powered
                    ? `AI content added using ${data.provider} (${data.model}). Review before saving.`
                    : 'Built-in writer used. Add a free AI_API_KEY in .env (OpenRouter or Groq) for real AI generation.';
            } catch (error) {
                this.aiError = error.message || 'Could not generate content.';
            } finally {
                this.aiLoading = null;
            }
        },

        parseAiTarget(target) {
            if (target.startsWith('experience:')) {
                return { field: 'experience_bullets', index: parseInt(target.split(':')[1], 10) || 0 };
            }
            if (target.startsWith('project:')) {
                return { field: 'project_description', index: parseInt(target.split(':')[1], 10) || 0 };
            }
            return { field: target, index: 0 };
        },

        applyAiContent(field, content, index = 0) {
            if (field === 'headline') {
                this.resume.headline = content;
                return;
            }
            if (field === 'summary') {
                this.resume.summary = content;
                return;
            }
            if (field === 'skills') {
                this.resume.skills_raw = content;
                return;
            }
            if (field === 'languages') {
                this.resume.languages_raw = content;
                return;
            }
            if (field === 'experience_bullets' && this.resume.experience[index]) {
                this.resume.experience[index].bullets = content;
                return;
            }
            if (field === 'project_description' && this.resume.projects[index]) {
                this.resume.projects[index].description = content;
                return;
            }
            if (field === 'full_resume' && typeof content === 'object') {
                if (content.headline) this.resume.headline = content.headline;
                if (content.summary) this.resume.summary = content.summary;
                if (content.skills_raw) this.resume.skills_raw = content.skills_raw;
                if (Array.isArray(content.experience_bullets) && this.resume.experience[0]) {
                    this.resume.experience[0].bullets = content.experience_bullets.join('\n');
                }
            }
        },
    };
}

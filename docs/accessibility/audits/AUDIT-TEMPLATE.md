# Component Accessibility Audit Template

**Component:** [Component Name]
**Tier:** [1-4]
**Complexity:** [Low/Medium/High/Very High]
**Blade File:** `resources/views/components/[name].blade.php`
**PHP Class:** `src/View/Components/[Name].php` (if exists)
**Date Audited:** YYYY-MM-DD
**Auditor:** [Name]

---

## Current State Analysis

### Semantic HTML
- [ ] Uses appropriate HTML5 semantic elements
- [ ] Proper element hierarchy
- [ ] Meaningful structure

**Current Implementation:**
```html
[Paste relevant HTML structure]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### ARIA Attributes
- [ ] Has proper ARIA roles
- [ ] Has ARIA labels where needed
- [ ] Has ARIA states (expanded, selected, etc.)
- [ ] Has ARIA properties (describedby, labelledby, etc.)
- [ ] No redundant or conflicting ARIA

**Current Implementation:**
```html
[Paste ARIA attributes if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Keyboard Navigation
- [ ] All interactive elements are keyboard accessible
- [ ] Proper tab order
- [ ] Arrow key navigation (if applicable)
- [ ] Enter/Space activation
- [ ] Escape key handling (if applicable)
- [ ] Home/End keys (if applicable)

**Current Implementation:**
```javascript
[Paste keyboard handling code if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Focus Management
- [ ] Visible focus indicators
- [ ] Proper focus order
- [ ] Focus trap (if modal/dialog)
- [ ] Focus restoration (if applicable)
- [ ] No keyboard traps

**Current Implementation:**
```html
[Paste focus-related code if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Screen Reader Support
- [ ] Proper labels for all interactive elements
- [ ] Alternative text for images/icons
- [ ] Screen reader announcements for dynamic content
- [ ] Live regions for status updates
- [ ] Hidden decorative elements (aria-hidden)

**Current Implementation:**
```html
[Paste screen reader related code if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Color & Contrast
- [ ] Sufficient color contrast (4.5:1 for normal text)
- [ ] Information not conveyed by color alone
- [ ] High contrast mode support
- [ ] Visible in dark/light modes

**Contrast Analysis:**
- Foreground: [color]
- Background: [color]
- Ratio: [X.X:1]
- Pass WCAG AA: Yes/No

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Forms & Validation
*(If applicable)*

- [ ] Labels associated with inputs
- [ ] Required fields indicated
- [ ] Error messages associated (aria-describedby)
- [ ] Error states indicated (aria-invalid)
- [ ] Help text associated (aria-describedby)

**Current Implementation:**
```html
[Paste form-related code if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing / ⚪ N/A

---

### Responsive & Mobile
- [ ] Touch targets are at least 44x44px
- [ ] Works with zoom (200%)
- [ ] Responsive to viewport changes
- [ ] Mobile screen reader tested

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing

---

### Animation & Motion
- [ ] Respects prefers-reduced-motion
- [ ] No auto-playing animations without control
- [ ] Motion can be paused/stopped

**Current Implementation:**
```css
[Paste animation code if any]
```

**Issues:**
- Issue 1
- Issue 2

**Score:** 🟢 Good / 🟡 Partial / 🔴 Missing / ⚪ N/A

---

## WCAG 2.1 AA Compliance Checklist

### Level A
- [ ] **1.1.1** Non-text Content - Alternative text
- [ ] **1.3.1** Info and Relationships - Semantic structure
- [ ] **1.3.2** Meaningful Sequence - Logical order
- [ ] **1.3.3** Sensory Characteristics - Not reliant on sensory info
- [ ] **2.1.1** Keyboard - Keyboard accessible
- [ ] **2.1.2** No Keyboard Trap - Focus not trapped
- [ ] **2.4.1** Bypass Blocks - Skip links (if applicable)
- [ ] **2.4.2** Page Titled - Descriptive title (if applicable)
- [ ] **2.4.3** Focus Order - Logical focus order
- [ ] **2.4.4** Link Purpose - Clear link text (if applicable)
- [ ] **3.2.1** On Focus - No unexpected context changes
- [ ] **3.2.2** On Input - No unexpected changes on input
- [ ] **3.3.1** Error Identification - Errors identified (if applicable)
- [ ] **3.3.2** Labels or Instructions - Form labels (if applicable)
- [ ] **4.1.1** Parsing - Valid HTML
- [ ] **4.1.2** Name, Role, Value - Proper ARIA

### Level AA
- [ ] **1.4.3** Contrast (Minimum) - 4.5:1 contrast ratio
- [ ] **1.4.5** Images of Text - No images of text
- [ ] **2.4.5** Multiple Ways - Multiple navigation methods
- [ ] **2.4.6** Headings and Labels - Descriptive headings
- [ ] **2.4.7** Focus Visible - Visible focus indicator
- [ ] **3.1.2** Language of Parts - Language changes marked
- [ ] **3.2.3** Consistent Navigation - Consistent navigation
- [ ] **3.2.4** Consistent Identification - Consistent components
- [ ] **3.3.3** Error Suggestion - Error suggestions provided
- [ ] **3.3.4** Error Prevention - Preventable errors

---

## Overall Score

| Category | Score |
|----------|-------|
| Semantic HTML | 🟢/🟡/🔴 |
| ARIA Attributes | 🟢/🟡/🔴 |
| Keyboard Navigation | 🟢/🟡/🔴 |
| Focus Management | 🟢/🟡/🔴 |
| Screen Reader Support | 🟢/🟡/🔴 |
| Color & Contrast | 🟢/🟡/🔴 |
| Forms & Validation | 🟢/🟡/🔴/⚪ |
| Responsive & Mobile | 🟢/🟡/🔴 |
| Animation & Motion | 🟢/🟡/🔴/⚪ |

**Overall Compliance:** [0-100]%

**Priority:** 🔴 High / 🟡 Medium / 🟢 Low

---

## Required Changes

### Critical (Must Fix)
1. Change description
2. Change description
3. Change description

### Important (Should Fix)
1. Change description
2. Change description
3. Change description

### Enhancement (Nice to Have)
1. Change description
2. Change description
3. Change description

---

## Implementation Estimate

**Effort:** [Low/Medium/High/Very High]
**Time Estimate:** [X hours/days]
**Dependencies:** [List any dependencies]
**Blockers:** [List any blockers]

---

## Code Examples

### Before (Current)
```blade
[Current implementation]
```

### After (Proposed)
```blade
[Proposed accessible implementation]
```

---

## Testing Checklist

- [ ] Keyboard-only navigation tested
- [ ] Screen reader tested (NVDA/JAWS/VoiceOver)
- [ ] Color contrast validated
- [ ] High contrast mode tested
- [ ] Reduced motion tested
- [ ] Zoom to 200% tested
- [ ] Mobile tested
- [ ] aXe DevTools - no violations
- [ ] WAVE - no errors

---

## Notes

[Additional notes, observations, or concerns]

---

## References

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [Component-specific patterns]

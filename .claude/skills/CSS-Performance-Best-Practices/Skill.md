# CSS Performance & Best Practices Skill

## Purpose

You are an expert frontend CSS engineer.

Your responsibility is to generate CSS that is:

- Fast
- Lightweight
- Maintainable
- Scalable
- Responsive
- Accessible
- Easy to debug
- Easy to extend
- Production-ready

When creating CSS from scratch, prioritize browser performance and maintainability over visual complexity.

The CSS should work efficiently on:

- Desktop
- Tablet
- Mobile
- Low-end devices
- Slow networks

---

# 1. Core Principles

Always follow these principles:

1. Write the minimum CSS necessary.
2. Avoid unnecessary selectors.
3. Avoid excessive specificity.
4. Avoid deeply nested selectors.
5. Reuse styles instead of duplicating them.
6. Prefer simple selectors.
7. Use modern CSS features when they improve simplicity or performance.
8. Avoid unnecessary JavaScript for visual behavior.
9. Avoid expensive animations.
10. Keep responsive CSS predictable.
11. Keep CSS architecture consistent.
12. Avoid premature abstraction.
13. Optimize for maintainability first, then performance.
14. Never sacrifice accessibility for visual optimization.

---

# 2. CSS Architecture

For a medium or large project, organize CSS into clear layers.

Recommended:

```text
resources/
└── css/
    ├── app.css
    ├── base/
    │   ├── reset.css
    │   ├── typography.css
    │   └── accessibility.css
    │
    ├── components/
    │   ├── button.css
    │   ├── card.css
    │   ├── modal.css
    │   ├── form.css
    │   └── navbar.css
    │
    ├── layouts/
    │   ├── container.css
    │   ├── grid.css
    │   └── header.css
    │
    ├── pages/
    │   ├── home.css
    │   ├── books.css
    │   └── profile.css
    │
    └── utilities/
        ├── spacing.css
        ├── visibility.css
        └── typography.css
```

For a small application, do not create dozens of files unnecessarily.

Use one or a few files until the project actually requires more structure.

---

# 3. CSS Cascade Layers

When supported by the project browser requirements, use CSS cascade layers to control architecture.

Example:

```css
@layer reset, base, components, utilities;

@layer reset {
    /* Reset */
}

@layer base {
    /* Base styles */
}

@layer components {
    /* Components */
}

@layer utilities {
    /* Utilities */
}
```

Recommended order:

```text
reset
base
layouts
components
utilities
overrides
```

Avoid using `!important` as a normal mechanism for managing specificity.

---

# 4. CSS Custom Properties

Use CSS variables for reusable design values.

Example:

```css
:root {
    --color-primary: #2563eb;
    --color-text: #111827;
    --color-muted: #6b7280;

    --space-1: 0.25rem;
    --space-2: 0.5rem;
    --space-3: 0.75rem;
    --space-4: 1rem;
    --space-6: 1.5rem;
    --space-8: 2rem;

    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;

    --container-width: 1200px;
}
```

Use variables for:

- Colors
- Spacing
- Typography
- Border radius
- Shadows
- Z-index layers
- Container widths
- Transitions

Do not create variables for every individual CSS value.

Bad:

```css
--button-left-padding: 17px;
--button-top-padding: 11px;
--card-title-margin: 13px;
```

Create variables only for values that represent reusable design decisions.

---

# 5. Design Tokens

For larger projects, establish a small design system.

Example:

```css
:root {
    /* Colors */
    --color-primary: #2563eb;
    --color-primary-hover: #1d4ed8;

    --color-surface: #ffffff;
    --color-background: #f8fafc;

    --color-text: #0f172a;
    --color-text-muted: #64748b;

    --color-border: #e2e8f0;

    /* Spacing */
    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;

    /* Layout */
    --container-max-width: 1200px;

    /* Radius */
    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
}
```

Keep the token system small and consistent.

---

# 6. Selector Performance

Prefer simple selectors.

Good:

```css
.card {
    padding: 1rem;
}
```

Good:

```css
.card-title {
    font-size: 1.25rem;
}
```

Avoid unnecessarily complex selectors:

```css
.page > .content > .container > .products > article > div > h2 {
    ...
}
```

Complex selectors:

- Increase specificity.
- Make maintenance harder.
- Create fragile CSS.
- Can increase style matching work.

Prefer component-level classes.

---

# 7. Avoid Deep Nesting

Avoid:

```css
.header {
    .navigation {
        .menu {
            .item {
                .link {
                    ...
                }
            }
        }
    }
}
```

Prefer:

```css
.header {
    ...
}

.nav-menu {
    ...
}

.nav-item {
    ...
}

.nav-link {
    ...
}
```

Keep selectors generally within 1–3 levels of conceptual nesting.

---

# 8. Specificity

Keep specificity low.

Prefer:

```css
.button {
    ...
}
```

over:

```css
main .content .card .button {
    ...
}
```

Avoid IDs for styling:

```css
#main-button {
    ...
}
```

Prefer classes:

```css
.button {
    ...
}
```

Avoid chaining unnecessary classes:

```css
.card.card-primary.card-large {
    ...
}
```

unless the combination has a real semantic purpose.

---

# 9. Avoid `!important`

Do not use:

```css
color: red !important;
```

unless there is a specific architectural reason.

If specificity is becoming difficult to manage:

1. Simplify selectors.
2. Fix CSS ordering.
3. Use cascade layers.
4. Refactor component styles.

`!important` should be an exception, not the architecture.

---

# 10. Box Sizing

Always establish predictable box sizing.

```css
*,
*::before,
*::after {
    box-sizing: border-box;
}
```

This makes layout calculations easier and more predictable.

---

# 11. Reset

Use a minimal reset rather than blindly importing a massive CSS framework.

Example:

```css
*,
*::before,
*::after {
    box-sizing: border-box;
}

html {
    line-height: 1.5;
    -webkit-text-size-adjust: 100%;
}

body {
    margin: 0;
}

img,
picture,
svg,
video {
    display: block;
    max-width: 100%;
}

button,
input,
textarea,
select {
    font: inherit;
}
```

Only add reset rules that solve an actual consistency problem.

---

# 12. Layout

Prefer modern layout systems.

Use:

- Flexbox
- CSS Grid
- Container queries
- Logical properties
- `gap`

Example:

```css
.card-grid {
    display: grid;
    grid-template-columns: repeat(
        auto-fit,
        minmax(min(100%, 280px), 1fr)
    );
    gap: 1.5rem;
}
```

Avoid manually positioning layouts with:

```css
position: absolute;
left: 123px;
top: 57px;
```

unless absolute positioning is actually appropriate.

---

# 13. Prefer `gap`

Instead of:

```css
.card + .card {
    margin-top: 1rem;
}
```

when working with flex/grid:

```css
.card-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
```

`gap` provides cleaner and more predictable spacing.

---

# 14. Avoid Excessive Fixed Dimensions

Avoid:

```css
.card {
    width: 437px;
    height: 312px;
}
```

Prefer flexible constraints:

```css
.card {
    width: 100%;
    max-width: 28rem;
}
```

Use fixed dimensions only when the design genuinely requires them.

---

# 15. Responsive Design

Use a mobile-first approach by default.

Base:

```css
.container {
    width: min(100% - 2rem, 1200px);
    margin-inline: auto;
}
```

Then progressively enhance:

```css
@media (min-width: 768px) {
    .container {
        width: min(100% - 3rem, 1200px);
    }
}
```

Avoid writing desktop CSS first and overriding almost everything for mobile.

---

# 16. Prefer Fluid CSS

Use modern functions where appropriate:

```css
font-size: clamp(1.5rem, 3vw, 3rem);
```

```css
padding-inline: clamp(1rem, 4vw, 4rem);
```

```css
width: min(100% - 2rem, 1200px);
```

This can reduce the number of media queries required.

---

# 17. Container Queries

For reusable components, prefer container queries when the component's layout should depend on its available space rather than the viewport.

Example:

```css
.card-container {
    container-type: inline-size;
}

.card {
    display: grid;
}

@container (min-width: 500px) {
    .card {
        grid-template-columns: 160px 1fr;
    }
}
```

Use media queries for page-level responsive behavior.

Use container queries for component-level responsive behavior.

---

# 18. Images

Prevent layout shifts.

Prefer:

```css
.book-cover {
    aspect-ratio: 2 / 3;
    width: 100%;
    object-fit: cover;
}
```

HTML:

```html
<img
    src="cover.webp"
    width="400"
    height="600"
    alt="Book cover"
>
```

Always provide dimensions when possible.

Use modern image formats such as:

- AVIF
- WebP

when appropriate.

Do not load unnecessarily large images.

---

# 19. Animations

Animations must prioritize compositor-friendly properties.

Prefer:

```css
transform
opacity
```

Example:

```css
.card {
    transition:
        transform 180ms ease,
        opacity 180ms ease;
}

.card:hover {
    transform: translateY(-4px);
}
```

Avoid animating:

```css
width
height
top
left
margin
padding
```

when a transform can achieve the same visual result.

---

# 20. Avoid Expensive Animations

Avoid unnecessary:

```css
box-shadow
filter
backdrop-filter
```

animations.

For example, avoid continuously animating:

```css
box-shadow: 0 0 50px ...
```

unless there is a strong visual reason.

Do not create dozens of simultaneously animated elements.

---

# 21. Respect Reduced Motion

Always consider users who prefer reduced motion.

Example:

```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        scroll-behavior: auto !important;
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

Use this carefully and avoid unnecessary `!important` outside this accessibility override.

---

# 22. Transitions

Do not use:

```css
transition: all 0.3s ease;
```

Prefer:

```css
transition:
    background-color 180ms ease,
    color 180ms ease,
    transform 180ms ease;
```

This prevents unintended properties from being animated.

---

# 23. Hover and Touch

Do not assume hover exists on every device.

Use:

```css
@media (hover: hover) and (pointer: fine) {
    .button:hover {
        ...
    }
}
```

This is particularly useful for interfaces that need to behave well on touch devices.

---

# 24. Typography

Define a consistent typography system.

Example:

```css
body {
    font-family:
        Inter,
        system-ui,
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        sans-serif;

    color: var(--color-text);
    background: var(--color-background);
}
```

Avoid loading many font families and weights.

Prefer a limited set of weights:

```text
400
500
600
700
```

Load only the weights actually used.

---

# 25. Font Performance

When using web fonts:

- Minimize font families.
- Minimize font weights.
- Prefer WOFF2.
- Preload only critical fonts.
- Avoid loading fonts that are not actually used.

Example:

```html
<link
    rel="preload"
    href="/fonts/inter-regular.woff2"
    as="font"
    type="font/woff2"
    crossorigin
>
```

Do not preload every font file.

---

# 26. Logical Properties

Prefer logical properties when supporting multilingual layouts.

Instead of:

```css
margin-left: 1rem;
```

prefer:

```css
margin-inline-start: 1rem;
```

Instead of:

```css
padding-left: 1rem;
padding-right: 1rem;
```

prefer:

```css
padding-inline: 1rem;
```

Instead of:

```css
text-align: left;
```

prefer:

```css
text-align: start;
```

This is particularly important for applications supporting RTL languages such as Arabic.

---

# 27. RTL Support

When supporting Arabic:

```css
html[dir="rtl"] {
    ...
}
```

Prefer logical properties instead of creating duplicated LTR/RTL rules.

Good:

```css
padding-inline-start: 1rem;
margin-inline-end: 1rem;
border-start-start-radius: 0.5rem;
```

Avoid:

```css
margin-left: 1rem;
```

followed by a separate RTL override.

---

# 28. Z-Index Architecture

Do not randomly use:

```css
z-index: 999999;
```

Define a small hierarchy.

Example:

```css
:root {
    --z-dropdown: 100;
    --z-sticky: 200;
    --z-overlay: 300;
    --z-modal: 400;
    --z-toast: 500;
}
```

Keep the number of stacking levels small.

---

# 29. Avoid Unnecessary DOM Dependencies

Avoid CSS such as:

```css
.page > div > div > section > article > div {
    ...
}
```

CSS should depend on meaningful classes and component structure.

Prefer:

```css
.book-card {
    ...
}
```

---

# 30. CSS Reuse

Avoid duplication.

Bad:

```css
.primary-button {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}

.secondary-button {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}
```

Prefer:

```css
.button {
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
}

.button--primary {
    ...
}

.button--secondary {
    ...
}
```

Separate shared structure from variants.

---

# 31. Component Naming

Use a consistent naming strategy.

For example:

```text
.block
.block__element
.block--modifier
```

Example:

```css
.book-card {
    ...
}

.book-card__title {
    ...
}

.book-card__author {
    ...
}

.book-card--featured {
    ...
}
```

Do not mix multiple naming conventions randomly.

---

# 32. Avoid Generic Class Collisions

Avoid overly generic classes in large applications:

```css
.title
.box
.container
.item
.content
.card
```

Prefer meaningful component names when collision is possible:

```css
.book-card
.book-card__title
.book-list
.profile-card
```

---

# 33. Utilities

Utilities can be useful for repeated one-purpose styles.

Example:

```css
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
```

Do not create hundreds of utility classes without a clear system.

---

# 34. Avoid CSS Hacks

Do not solve layout problems using random:

```css
transform: translateX(3px);
```

or:

```css
margin-top: -17px;
```

unless the negative value has a legitimate design purpose.

If many hacks are required, reconsider the layout structure.

---

# 35. CSS Nesting

Native CSS nesting may be used when the project browser support allows it.

Good:

```css
.card {
    padding: 1rem;

    &:hover {
        transform: translateY(-2px);
    }

    .card__title {
        font-weight: 700;
    }
}
```

Do not create deeply nested CSS.

Keep nesting shallow.

---

# 36. Modern CSS Features

Use modern CSS when it simplifies the implementation.

Useful features include:

- CSS Grid
- Flexbox
- `gap`
- Custom properties
- `clamp()`
- `min()`
- `max()`
- `minmax()`
- Container queries
- `aspect-ratio`
- Logical properties
- `color-mix()`
- CSS nesting
- Cascade layers

Do not use a modern feature merely because it exists.

Browser support and project requirements always matter.

---

# 37. Avoid JavaScript for CSS Problems

Do not use JavaScript to calculate basic layout.

Avoid:

```javascript
element.style.width = window.innerWidth / 2 + 'px';
```

when CSS can solve the problem:

```css
grid-template-columns: repeat(2, 1fr);
```

Use CSS for:

- Layout
- Responsive behavior
- Visibility
- Hover
- Focus
- Transitions
- Animations

Use JavaScript only when actual application state or interaction requires it.

---

# 38. Critical Rendering Performance

Avoid blocking the initial render with unnecessary CSS.

For production:

- Bundle CSS efficiently.
- Minify CSS.
- Remove unused CSS.
- Avoid importing massive libraries for a few components.
- Load page-specific CSS when appropriate.
- Avoid excessive third-party stylesheets.

For critical above-the-fold styles, consider critical CSS strategies when performance measurements justify them.

Do not prematurely inline the entire stylesheet.

---

# 39. Avoid Huge CSS Frameworks When Unnecessary

If the project needs only a small design system, do not import an entire UI framework simply to obtain:

```text
button
card
container
grid
```

Prefer custom CSS when the application requirements are small and specific.

However, do not reinvent an entire design system unnecessarily if a framework already fits the project's needs.

---

# 40. CSS File Imports

Avoid creating long chains of CSS imports in production.

Bad:

```css
@import "buttons.css";
@import "cards.css";
@import "forms.css";
@import "navigation.css";
@import "modal.css";
```

Prefer bundling through the project's build system such as Vite.

Example:

```css
@layer reset, base, layouts, components, utilities;
```

Then let the build process produce optimized assets.

---

# 41. Vite Integration

For Laravel + Vite:

```css
resources/css/app.css
```

should be the main entry point.

Example:

```js
import './bootstrap';
import '../css/app.css';
```

Use Vite to:

- Bundle
- Minify
- Version assets
- Optimize production delivery

Production:

```bash
npm run build
```

Do not manually concatenate CSS files.

---

# 42. Dark Mode

If dark mode is required, use a consistent strategy.

CSS variables are often preferable:

```css
:root {
    --color-background: #ffffff;
    --color-text: #111827;
}

[data-theme="dark"] {
    --color-background: #111827;
    --color-text: #f9fafb;
}
```

Components then remain unchanged:

```css
.card {
    background: var(--color-background);
    color: var(--color-text);
}
```

Avoid duplicating every component's entire CSS for dark mode.

---

# 43. Accessibility

Always style:

```css
:focus-visible
```

Example:

```css
button:focus-visible,
a:focus-visible {
    outline: 2px solid currentColor;
    outline-offset: 3px;
}
```

Never remove focus indicators without replacing them with an accessible alternative.

Example:

```css
button {
    min-height: 44px;
}
```

when appropriate for touch targets.

---

# 44. Content Visibility

For long pages containing many independent sections, consider:

```css
.section {
    content-visibility: auto;
    contain-intrinsic-size: auto 500px;
}
```

Use this only when appropriate and after understanding the rendering implications.

Do not apply it globally.

---

# 45. CSS Containment

Use containment when it provides a measurable benefit for complex components.

Example:

```css
.large-widget {
    contain: layout paint;
}
```

Do not add `contain` everywhere.

---

# 46. Performance Anti-Patterns

Avoid:

```css
* {
    transition: all 0.3s;
}
```

Avoid:

```css
* {
    animation: ...
}
```

Avoid:

```css
div div div div {
    ...
}
```

Avoid:

```css
.box {
    position: absolute;
    left: 10%;
    top: 50%;
}
```

when Flexbox/Grid can solve the layout.

Avoid:

```css
width: 100vw;
```

for normal page containers because it can create horizontal overflow when scrollbars are present.

Prefer:

```css
width: 100%;
```

Avoid unnecessary:

```css
will-change: transform;
```

`will-change` should only be used for elements that genuinely benefit from compositor promotion.

---

# 47. Avoid Layout Thrashing

CSS itself should not force unnecessary layout recalculations through excessive dynamic style changes.

When JavaScript interacts with CSS:

Prefer toggling classes:

```javascript
element.classList.toggle('is-open');
```

instead of repeatedly changing individual inline styles:

```javascript
element.style.width = ...
element.style.height = ...
element.style.margin = ...
```

Use CSS classes and custom properties where appropriate.

---

# 48. Print Styles

If the application generates printable pages, add a dedicated print strategy.

Example:

```css
@media print {
    .no-print {
        display: none;
    }

    .print-content {
        color: #000;
        background: #fff;
    }
}
```

Do not optimize print CSS at the expense of the normal application.

---

# 49. Code Formatting

Use consistent formatting.

Example:

```css
.book-card {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
}
```

Group properties logically:

1. Layout
2. Box model
3. Typography
4. Visual
5. Animation

Example:

```css
.button {
    /* Layout */
    display: inline-flex;
    align-items: center;
    justify-content: center;

    /* Box */
    padding: 0.75rem 1rem;

    /* Typography */
    font-weight: 600;

    /* Visual */
    color: white;
    background: var(--color-primary);
    border-radius: var(--radius-md);

    /* Motion */
    transition:
        background-color 180ms ease,
        transform 180ms ease;
}
```

---

# 50. Production Optimization

Before production:

1. Build CSS with the production build system.
2. Minify CSS.
3. Remove unused CSS.
4. Check bundle size.
5. Optimize fonts.
6. Optimize images.
7. Check layout shifts.
8. Check animation performance.
9. Test mobile devices.
10. Test slow network conditions.

Use real performance measurements rather than assumptions.

---

# 51. Performance Validation

When optimizing CSS, evaluate:

- CSS file size
- Number of stylesheets
- Render-blocking resources
- Largest Contentful Paint
- Cumulative Layout Shift
- Interaction responsiveness
- Long-running animations
- Unused CSS

Use browser DevTools and Lighthouse/PageSpeed Insights where appropriate.

Do not claim that a CSS change improves performance without reasonable evidence.

---

# 52. Component Creation Process

When creating a component from scratch:

### Step 1 — Define structure

Identify:

```text
Component
├── container
├── content
├── title
├── actions
└── states
```

### Step 2 — Define tokens

Reuse existing:

- Colors
- Spacing
- Typography
- Radius

### Step 3 — Build layout

Prefer:

```text
Grid
Flexbox
Gap
Intrinsic sizing
```

### Step 4 — Add responsive behavior

Start mobile-first.

### Step 5 — Add states

Consider:

```text
default
hover
focus
active
disabled
loading
error
selected
```

### Step 6 — Add accessibility

Check:

- Focus
- Contrast
- Keyboard interaction
- Reduced motion
- Touch targets

### Step 7 — Optimize

Remove:

- Duplicate declarations
- Unnecessary selectors
- Unused rules
- Unnecessary animations

---

# 53. Before Generating CSS

Before writing CSS, determine:

1. Is this a reusable component?
2. Does an existing class already solve the problem?
3. Can Flexbox/Grid solve the layout?
4. Can CSS variables be reused?
5. Does this need a media query?
6. Would a container query be better?
7. Does this require JavaScript?
8. Does the component need RTL support?
9. Does the animation affect layout?
10. Can the implementation be simpler?

Never immediately generate CSS without considering these questions.

---

# 54. Final CSS Quality Checklist

Before considering CSS complete, verify:

### Architecture

- [ ] Clear organization
- [ ] Consistent naming
- [ ] Low specificity
- [ ] Minimal nesting
- [ ] No unnecessary duplication

### Performance

- [ ] No unnecessary animations
- [ ] No `transition: all`
- [ ] No excessive `will-change`
- [ ] No huge unused dependencies
- [ ] No unnecessary CSS imports
- [ ] Production CSS is minified
- [ ] Unused CSS is removed where appropriate

### Layout

- [ ] Flexbox/Grid used appropriately
- [ ] `gap` used where appropriate
- [ ] Responsive
- [ ] Mobile-first
- [ ] No unnecessary absolute positioning
- [ ] No unnecessary fixed dimensions

### Accessibility

- [ ] Focus states exist
- [ ] Keyboard navigation is supported
- [ ] Reduced motion considered
- [ ] Sufficient contrast
- [ ] Touch targets are usable

### Internationalization

- [ ] RTL considered
- [ ] Logical CSS properties used
- [ ] No unnecessary left/right assumptions

### Maintainability

- [ ] CSS variables used appropriately
- [ ] Components are reusable
- [ ] Naming is consistent
- [ ] No CSS hacks
- [ ] No unnecessary `!important`

---

# Final Principle

Always prefer:

> **Simple CSS + modern layout + low specificity + reusable components + minimal JavaScript + measured optimization.**

The goal is not to write the most sophisticated CSS.

The goal is to write the **smallest, clearest, fastest CSS that correctly solves the problem and remains maintainable as the application grows.**
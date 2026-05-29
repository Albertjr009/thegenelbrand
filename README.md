# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Oxc](https://oxc.rs)
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/)

## React Compiler

The React Compiler is enabled on this template. See [this documentation](https://react.dev/learn/react-compiler) for more information.

Note: This will impact Vite dev & build performances.

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.

## Fashion student portfolio (Tailwind)

This workspace now includes a simple React + Tailwind setup and components for a fashion-student portfolio (hero, skills, portfolio grid, contact footer).

Quick start:

1. Install required dev dependencies for Tailwind:

```bash
npm install -D tailwindcss postcss autoprefixer
```

2. Start the dev server:

```bash
npm run dev
```

Files added:
- `tailwind.config.cjs` — Tailwind config
- `postcss.config.cjs` — PostCSS config
- `src/components/*` — Header, Hero, Services, Portfolio, Footer

Notes:
- We used `src/assets/hero.png` as a placeholder for portfolio images. Replace with your photography or sketches.
- If you want Tailwind CLI generated files, run `npx tailwindcss init` and adjust `content` in the config as needed.

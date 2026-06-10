# FAY Portfolio Website — PRD v1.0

## Overview

Portfolio website yang berfungsi sebagai digital home untuk seluruh produk, proyek, dan eksperimen yang dibangun oleh FAY.

Website ini bukan CV online dan bukan landing page jasa.

Fokus utama adalah menunjukkan karya, proses berpikir, dan perjalanan membangun produk sehingga pengunjung terdorong untuk mengikuti perkembangan FAY di media sosial.

Core message:

> I build real products.

Primary goal:

> Follow my journey.

Secondary goal:

> Explore my work.

Tertiary goal:

> Collaboration opportunities.

---

# Target Audience

## Primary

* Founders
* Indie Hackers
* Startup Builders
* Product Builders

## Secondary

* Tech Community
* Developers

## Tertiary

* Recruiters
* Potential Clients

---

# Positioning

Young Product Builder & Full-Stack Developer

Problem Solver → Product Builder → Lifelong Learner

Differentiation:

* Young builder with real projects
* Understands both product and business
* Builds publicly
* Focuses on solving problems instead of showcasing technologies

---

# Design Direction

Style:

* Minimal Modern
* Modern Startup Portfolio
* Clean Product Showcase
* Apple-inspired Presentation
* Elegant and Professional

Theme:

* Light Mode First
* Soft and Comfortable Visual Experience
* Minimalist with Modern Touch

Color Palette:

Background: #F7F6F3
Surface: #FFFFFF
Card: #FDFDFC
Border: #E8E6E1
Text Primary: #1F2937
Text Secondary: #6B7280
Accent: #8B5CF6
Accent Soft: #EDE9FE

Alternative Accent Options:

* Soft Purple (#8B5CF6)
* Soft Blue (#60A5FA)
* Soft Sage (#84CCB4)

Visual Principles:

* Large typography
* Premium spacing
* Clean layouts
* Soft shadows
* Rounded corners
* Product-first visuals
* Real screenshots
* Subtle gradients
* Smooth and lightweight animations

Design Feel:

* Bright but not overly white
* Calm and modern
* Professional yet approachable
* Focused on readability and content
* Premium without feeling crowded

---

# Tech Stack

Version: MVP

Frontend:

* HTML
* CSS
* JavaScript

Deployment:

* Vercel
  or
* Netlify

Assets:

* Optimized WebP images

Icons:

* Lucide Icons

Fonts:

* Inter
* Geist

No framework.

No build process.

No dependencies.

Single-page architecture.

Fast deployment prioritized.

---

# Sitemap

/

Single-page portfolio

Sections:

1. Hero
2. Featured Work
3. What I Build
4. About FAY
5. Building In Public
6. Project Archive
7. What's Next
8. Footer

---

# Section 1 — Hero

Purpose:

Introduce FAY and immediately establish credibility.

Layout:

Two-column desktop.

Left:

* Headline
* Description
* CTA

Right:

* Professional photo
* Floating project cards

Content:

Badge:

PRODUCT BUILDER

Headline:

Problem Solver, Product Builder, Lifelong Learner.

Description:

Building real products and sharing the journey publicly.

CTA Primary:

Explore My Work

CTA Secondary:

Follow My Journey

Stats:

10+ Projects Built

X Technologies

2026 Building Journey

Optional Floating Cards:

* Visura
* Travel CRM
* Threads CMS

---

# Section 2 — Featured Work

Purpose:

Show best projects first.

Layout:

Alternating showcase blocks.

Featured Projects:

1. Visura
2. Travel CRM
3. Threads CMS
4. ProdigManager
5. Additional flagship project

Project Structure:

Project Name

Short Description

Problem

Solution

Tech Stack

Screenshot

Buttons:

* View Project
* View Source

Project Card Fields:

{
title,
description,
image,
technologies[],
demo_url,
github_url
}

---

# Section 3 — What I Build

Purpose:

Show categories instead of skills.

Layout:

4 cards.

Categories:

AI Products

Business Systems

Web Applications

Future Experiments

Each card contains:

* Description
* Example projects

No technology logos.

Focus on outcomes.

---

# Section 4 — About FAY

Purpose:

Explain mindset.

Subsections:

What I Believe

Technology should solve real problems.

How I Build

Problem
↓
Research
↓
MVP
↓
Feedback
↓
Iteration

Current Focus

* Product Development
* AI
* Business Systems
* Emerging Technologies

Photo optional.

---

# Section 5 — Building In Public

Purpose:

Drive social follows.

Layout:

Large social cards.

Platforms:

GitHub

LinkedIn

Instagram

Threads

Card Content:

Platform

Short Description

Follower Count (optional)

CTA

Examples:

Follow on LinkedIn

See My GitHub

Read My Threads

---

# Section 6 — Project Archive

Purpose:

Show consistency.

Layout:

Responsive grid.

Projects:

* Lynk Manager
* Trading Journal
* ImageLover
* Wishfulist
* UI Prompter
* AI Fake Project Generator
* Etc.

Fields:

Name

Category

Short Description

Link

No case study required.

---

# Section 7 — What's Next

Purpose:

Show future direction.

Title:

Currently Building

Items:

Visura

Portfolio Content Studio

AI Experiments

Business Tools

Format:

Roadmap cards.

Simple and lightweight.

---

# Section 8 — Footer

Content:

FAY

Problem Solver, Product Builder, Lifelong Learner

Links:

GitHub

LinkedIn

Instagram

Threads

Copyright

---

# Animation Guidelines

Use only:

Fade Up

Fade In

Hover Scale

Soft Glow Accent

Counter Animation

Avoid:

Parallax

Heavy Particles

Complex 3D

WebGL

Three.js

Keep performance first.

Animations should feel:

* Smooth
* Elegant
* Minimal
* Non-distracting

---

# SEO

Title:

FAY — Product Builder & Full-Stack Developer

Description:

Building real products and sharing the journey publicly.

Open Graph:

Photo

Featured project screenshot

Keywords:

product builder
full stack developer
web developer
portfolio
ai builder
software engineer

---

# Performance Requirements

Lighthouse:

Performance > 90

Accessibility > 90

Best Practices > 90

SEO > 90

Initial Load:

< 2 seconds

Image Optimization:

WebP

Lazy Loading

No unnecessary libraries

---

# Future Roadmap (v2)

* Blog
* Project Case Studies
* Timeline
* Newsletter
* Analytics Dashboard
* CMS
* MDX Content
* Multi-language
* Dark/Light Toggle
* Command Palette
* Advanced Animations

Current v1 goal:

Launch quickly.

Show products.

Build audience.

Iterate later.

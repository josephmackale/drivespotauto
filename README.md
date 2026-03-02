# DriveSpot Auto

DriveSpot Auto is an automotive e-commerce platform focused on **accurate vehicle-to-part compatibility** through structured fitment modeling.

The system is designed to eliminate incorrect part matching by anchoring compatibility at the **engine level**, not just make/model.

---

## 🎯 The Problem

In many parts markets, compatibility is determined using only:

- Make
- Model name
- Approximate year

This leads to:
- Incorrect parts supplied
- High return rates
- Loss of customer trust
- Manual compatibility verification

DriveSpot addresses this by modeling fitment using a deterministic relational structure inspired by TecDoc-style data organization.

---

## 🏗 Fitment Data Architecture

Vehicle compatibility is represented using a normalized hierarchy:

Make → Model → Generation → Engine → Product Fitment

### Core Tables

- `vs_makes`
- `vs_models`
- `vs_generations`
- `vs_vehicle_engines`
- `product_vehicle_fitments`

Each engine becomes the authoritative compatibility anchor.

---

## 🔑 Canonical Engine Identification

Every engine entry generates a canonical key:

make-model-generation-yearfrom-yearto-enginecode[-drivetrain]

Example:

volvo-xc90-i-275-2002-2014-b5254t2-awd

This enables:

- Deterministic compatibility mapping
- Structured search indexing
- SEO-friendly product association
- Elimination of ambiguous “fits many models” logic

---

## ⚙ Platform Layer

The commerce layer provides catalog, cart, and order infrastructure,
while compatibility logic, schema design, and indexing are custom implemented.

---

## 🛠 Tech Stack

- Laravel (PHP)
- MySQL relational modeling
- Blade frontend
- VPS deployment (Ubuntu)

---

## 📌 Project Status

Active development.

Current focus:
- Fitment dataset expansion
- Vehicle selector API
- Catalog structuring
- Compatibility indexing

---

## Notes

This repository represents the engineering layer of the DriveSpot platform.
Operational data, suppliers, and production configuration are intentionally excluded.

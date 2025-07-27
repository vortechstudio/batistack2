# 🏗️ Batistack - ERP Moderne pour le BTP

![Banner](.github/assets/banner.png)

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-4B56BE?style=for-the-badge&logo=livewire&logoColor=white)](https://laravel-livewire.com)
[![License](https://img.shields.io/github/license/vortechstudio/batistack2?style=for-the-badge)](LICENSE)
[![Release](https://img.shields.io/github/v/release/vortechstudio/batistack2?style=for-the-badge)](https://github.com/vortechstudio/batistack2/releases)

**Solution tout-en-un** de gestion de projets de construction avec modules intégrés pour :
- 🏗️ Chantiers et planning
- 👥 Ressources humaines
- 📦 Stocks et logistique
- 💰 Facturation et comptabilité
- 📊 Business Intelligence

## ✨ Fonctionnalités

| Module | Description |
|--------|-------------|
| **Chantiers** | Suivi temps réel des coûts, planning Gantt, gestion des sous-traitants |
| **RH** | Paie automatisée, contrats électroniques, gestion des compétences |
| **Commerce** | Devis→Facture automatisé, gestion des fournisseurs, tableau de bord financier |
| **GPAO** | Ordonnancement de production, contrôle qualité IoT, traçabilité matière |
| **Mobile** | Application terrain avec synchronisation offline |

## 📚 Documentation

Consultez notre documentation complète :
- [Guide d'installation](resources/docs/1.0/getting-started.md)
- [Changelog](CHANGELOG.md)
- [Roadmap](ROADMAP.md)

## 🛠️ Architecture

```mermaid
graph TD
  A[Frontend] -->|Livewire| B[Backend]
  B --> C{Modules}
  C --> D[Chantiers]
  C --> E[RH]
  C --> F[Commerce]
  C --> G[GPAO]
  B --> H[API REST]
  H --> I[App Mobile]
  H --> J[Intégrations Tierces]
```

## 📸 Captures d'écran

| Tableau de bord | Fiche chantier |
|-----------------|----------------|
| ![Dashboard](.github/screenshots/dashboard.png) | ![Chantier](.github/screenshots/chantier.png) |

## 🤝 Contribution

Consultez notre [guide de contribution](.github/CONTRIBUTING.md) et les [bonnes pratiques](.github/CODE_OF_CONDUCT.md).

## 📄 License

MIT License - Voir le fichier [LICENSE](LICENSE)

---

**Batistack** © 2024 - [Documentation technique](resources/docs/1.0/)

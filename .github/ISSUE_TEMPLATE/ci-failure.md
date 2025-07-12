## Echec de Test sur la branche ${{ github.branch.name }}
### Context
- Workflow: ${{ github.workflow }}
- Commit: ${{ github.sha }}

### Résultat des tests
${{ process.env.TEST_RESULTS }}

### Checklist
- [ ] Vérifier les logs du workflow
- [ ] Reproduire localement
- [ ] Analyser les changements récents

**Assignation automatique** :
@tech-lead @devops,
labels: ['bug', 'ci'],
assignees: ['tech-lead', 'vortechstudio']

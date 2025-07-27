// Configuration de Chart.js pour Alpine.js
document.addEventListener('alpine:init', () => {
    // Fonction globale chart pour compatibilité avec Filament
    window.chart = function(config) {
        return {
            chart: null,
            
            init() {
                this.$nextTick(() => {
                    const canvas = this.$el.querySelector('canvas');
                    if (canvas && window.Chart) {
                        const ctx = canvas.getContext('2d');
                        this.chart = new window.Chart(ctx, {
                            type: config.type || 'doughnut',
                            data: config.cachedData || config.data,
                            options: config.options || {}
                        });
                    }
                });
            },
            
            destroy() {
                if (this.chart) {
                    this.chart.destroy();
                }
            }
        };
    };
    
    // Alternative: Ajouter chart comme directive Alpine
    Alpine.directive('chart', (el, { expression }, { evaluate }) => {
        const config = evaluate(expression);
        
        if (window.Chart && el.tagName === 'CANVAS') {
            const ctx = el.getContext('2d');
            new window.Chart(ctx, {
                type: config.type || 'doughnut',
                data: config.cachedData || config.data,
                options: config.options || {}
            });
        }
    });
});
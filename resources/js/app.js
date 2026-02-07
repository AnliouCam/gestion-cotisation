/**
 * FICHIER JAVASCRIPT PRINCIPAL
 *
 * Configure Alpine.js pour l'interactivité de l'interface
 * Alpine.js est utilisé pour les composants dynamiques (menus, modals, etc.)
 */

import './bootstrap';

// Importer Alpine.js
import Alpine from 'alpinejs';

// Rendre Alpine disponible globalement (pour les composants Blade)
window.Alpine = Alpine;

// Démarrer Alpine.js
Alpine.start();

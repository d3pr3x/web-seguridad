/**
 * Formateador de RUT chileno
 * Formatea automáticamente el RUT mientras el usuario escribe
 */

class RutFormatter {
    constructor() {
        this.init();
    }

    init() {
        // Aplicar formateo a todos los campos con clase 'rut-input'
        document.addEventListener('DOMContentLoaded', () => {
            this.applyToAllRutInputs();
        });

        // Aplicar también a campos que se agreguen dinámicamente
        const observer = new MutationObserver(() => {
            this.applyToAllRutInputs();
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    applyToAllRutInputs() {
        const rutInputs = document.querySelectorAll('.rut-input, input[name="rut"]');
        rutInputs.forEach(input => {
            if (!input.dataset.rutFormatted) {
                this.formatInput(input);
                input.dataset.rutFormatted = 'true';
            }
        });
    }

    formatInput(input) {
        // Formatear mientras escribe
        input.addEventListener('input', (e) => {
            e.target.value = this.formatRut(e.target.value);
        });

        // Permitir solo caracteres válidos
        input.addEventListener('keypress', (e) => {
            const char = String.fromCharCode(e.which);
            if (!/[0-9kK]/.test(char) && e.which !== 8 && e.which !== 46 && e.which !== 45) {
                e.preventDefault();
            }
        });

        // Limpiar al pegar
        input.addEventListener('paste', (e) => {
            setTimeout(() => {
                e.target.value = this.formatRut(e.target.value);
            }, 10);
        });
    }

    formatRut(value) {
        // Limpiar el valor, mantener solo números y K
        let cleanValue = value.replace(/[^0-9kK]/g, '');
        let formattedValue = '';

        if (cleanValue.length > 0) {
            // Separar el dígito verificador del resto
            let rut = cleanValue.slice(0, -1);
            let dv = cleanValue.slice(-1);

            // Formatear el RUT con puntos
            if (rut.length > 0) {
                // Agregar puntos cada 3 dígitos desde la derecha
                formattedValue = rut.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // Agregar el dígito verificador con guión
            if (dv.length > 0) {
                formattedValue += '-' + dv.toUpperCase();
            }
        }

        return formattedValue;
    }

    // Método estático para limpiar RUT (sin formato)
    static cleanRut(rut) {
        return rut.replace(/[^0-9kK]/g, '').toUpperCase();
    }

    // Método estático para validar RUT
    static validateRut(rut) {
        const cleanRut = this.cleanRut(rut);
        
        // Verificar formato básico
        if (!/^[0-9]{7,8}[0-9kK]$/.test(cleanRut)) {
            return false;
        }

        // Algoritmo de validación del dígito verificador
        const rutNumbers = cleanRut.slice(0, -1);
        const dv = cleanRut.slice(-1);
        
        let sum = 0;
        let multiplier = 2;
        
        for (let i = rutNumbers.length - 1; i >= 0; i--) {
            sum += parseInt(rutNumbers[i]) * multiplier;
            multiplier = multiplier === 7 ? 2 : multiplier + 1;
        }
        
        const remainder = sum % 11;
        const calculatedDv = remainder === 0 ? '0' : remainder === 1 ? 'K' : (11 - remainder).toString();
        
        return dv === calculatedDv;
    }
}

// Inicializar el formateador
new RutFormatter();

// Hacer disponible globalmente
window.RutFormatter = RutFormatter;



class ProductCreateAjax {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currentStep = 1;
        this.totalSteps = 7;
        this.isSubmitting = false;
        this.init();
    }
    
    init() {
        this.bindFormSubmission();
        this.bindNavigationButtons();
        this.setupCSRFToken();
    }
    
    setupCSRFToken() {}
    
    bindFormSubmission() {
        document.addEventListener('submit', (e) => {
            if (e.target.id === 'stepForm') {
                e.preventDefault();
                if (this.isSubmitting) return;
                const form = e.target;
                const formData = new FormData(form);
                this.submitStep(form.action, formData);
            }
        });
    }
    
    bindNavigationButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.id === 'nextBtn') {
                e.preventDefault();
                const form = document.getElementById('stepForm');
                if (form) {
                    form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
                }
            }
            if (e.target.id === 'prevBtn') {
                e.preventDefault();
                this.goToPreviousStep();
            }
        });
    }
    
    async submitStep(actionUrl, formData) {
        try {
            this.isSubmitting = true;
            this.showLoading();
            this.clearErrors();
            this.updateRichTextFields();
            
            const response = await fetch(actionUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const htmlText = await response.text();
                if (response.status === 422) {
                    this.showErrors({ general: ['Validation failed. Please check your inputs and try again.'] });
                } else if (response.status >= 500) {
                    this.showErrors({ general: ['Server error occurred. Please try again.'] });
                } else {
                    this.showErrors({ general: ['Switching to standard form submission...'] });
                    setTimeout(() => {
                        this.submitFormNormally();
                    }, 1000);
                }
                return;
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess(data.message);
                if (data.redirect_url) {
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 1500);
                } else if (data.next_step_url) {
                    setTimeout(() => {
                        this.navigateToStep(data.next_step_url);
                    }, 1000);
                }
            } else {
                this.showErrors(data.errors || { general: [data.message] });
            }
            
        } catch (error) {
            if (error.message.includes('JSON') || error.message.includes('Unexpected token')) {
                this.showErrors({ general: ['Switching to standard form submission...'] });
                setTimeout(() => {
                    this.submitFormNormally();
                }, 1000);
            } else {
                this.showErrors({ general: ['An unexpected error occurred. Please try again.'] });
            }
        } finally {
            this.isSubmitting = false;
            this.hideLoading();
        }
    }
    
    async navigateToStep(stepUrl) {
        try {
            this.showLoading('Loading next step...');
            setTimeout(() => {
                window.location.href = stepUrl;
            }, 500);
        } catch (error) {
            window.location.href = stepUrl;
        }
    }
    
    goToPreviousStep() {
        const currentStep = this.getCurrentStepNumber();
        if (currentStep > 1) {
            const prevStepUrl = this.getStepUrl(currentStep - 1);
            this.navigateToStep(prevStepUrl);
        }
    }
    
    submitFormNormally() {
        const form = document.getElementById('stepForm');
        if (form) {
            this.updateRichTextFields();
            form.removeEventListener('submit', this.handleFormSubmit);
            form.submit();
        }
    }
    
    getCurrentStepNumber() {
        const path = window.location.pathname;
        const match = path.match(/step-(\d+)/);
        return match ? parseInt(match[1]) : 1;
    }
    
    getStepUrl(stepNumber) {
        const baseUrl = window.location.origin;
        return `${baseUrl}/admin/products/create/step-${stepNumber}`;
    }
    
    updateRichTextFields() {
        if (typeof shortDescriptionQuill !== 'undefined') {
            const shortDescHidden = document.getElementById('shortDescriptionHidden');
            if (shortDescHidden) {
                shortDescHidden.value = shortDescriptionQuill.root.innerHTML;
            }
        }
        if (typeof detailedDescriptionQuill !== 'undefined') {
            const detailedDescHidden = document.getElementById('detailedDescriptionHidden');
            if (detailedDescHidden) {
                detailedDescHidden.value = detailedDescriptionQuill.root.innerHTML;
            }
        }
        if (typeof additionalInformationQuill !== 'undefined') {
            const additionalInfoHidden = document.getElementById('additionalInformationHidden');
            if (additionalInfoHidden) {
                additionalInfoHidden.value = additionalInformationQuill.root.innerHTML;
            }
        }
    }
    
    showLoading(message = 'Processing...') {
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        
        if (nextBtn) {
            nextBtn.disabled = true;
            nextBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                ${message}
            `;
        }
        
        if (prevBtn) {
            prevBtn.disabled = true;
            prevBtn.style.opacity = '0.5';
        }
        
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
    }
    
    hideLoading() {
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        
        if (nextBtn) {
            nextBtn.disabled = false;
            const currentStep = this.getCurrentStepNumber();
            if (currentStep === this.totalSteps) {
                nextBtn.innerHTML = '✨ Create Product';
            } else {
                nextBtn.innerHTML = 'Next Step →';
            }
        }
        
        if (prevBtn) {
            prevBtn.disabled = false;
            prevBtn.style.opacity = '1';
        }
        
        // Hide loading overlay
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }
    
    showSuccess(message) {
        this.showToast(message, 'success');
    }
    
    showErrors(errors) {
        // Clear previous errors
        this.clearErrors();
        
        // Show field-specific errors
        Object.keys(errors).forEach(fieldName => {
            const fieldErrors = errors[fieldName];
            if (Array.isArray(fieldErrors) && fieldErrors.length > 0) {
                this.showFieldError(fieldName, fieldErrors[0]);
            }
        });
        
        // Show general error toast
        if (errors.general) {
            this.showToast(errors.general[0], 'error');
        } else {
            const firstError = Object.values(errors)[0];
            if (Array.isArray(firstError) && firstError.length > 0) {
                this.showToast(firstError[0], 'error');
            }
        }
    }
    
    showFieldError(fieldName, errorMessage) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.remove('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
            let errorElement = field.parentElement.querySelector('.error-message');
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.className = 'error-message text-red-500 text-sm mt-1';
                field.parentElement.appendChild(errorElement);
            }
            errorElement.textContent = errorMessage;
        }
    }
    
    clearErrors() {
        document.querySelectorAll('.border-red-500').forEach(field => {
            field.classList.remove('border-red-500', 'focus:border-red-500', 'focus:ring-red-500');
            field.classList.add('border-gray-300', 'focus:border-blue-500', 'focus:ring-blue-500');
        });
        document.querySelectorAll('.error-message').forEach(errorElement => {
            errorElement.remove();
        });
    }
    
    showToast(message, type = 'info') {
        let toastContainer = document.getElementById('toastContainer');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(toastContainer);
        }
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ';
        
        toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 transform translate-x-full transition-transform duration-300 ease-in-out`;
        toast.innerHTML = `
            <span class="text-lg font-bold">${icon}</span>
            <span class="font-medium">${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        
        toastContainer.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
        }, 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }, 5000);
    }
    
    reinitializeComponents() {}
}

if (typeof window.productCreateAjax === 'undefined') {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            window.productCreateAjax = new ProductCreateAjax();
        });
    } else {
        window.productCreateAjax = new ProductCreateAjax();
    }
}

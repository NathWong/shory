import {Controller} from '@hotwired/stimulus';

export default class extends Controller<HTMLElement> {
    static targets = ['panel'];

    private readonly collapsedClass: string = 'collapsed';
    declare readonly panelTarget: HTMLDivElement;
    declare readonly hasPanelTarget: boolean;

    connect() {
        window.addEventListener('turbo:frame-load', () => this.checkIfEmpty())
        // wait to be sure turbo has time to do his stuff
        setTimeout(() => this.checkIfEmpty(), 0);
    }

    disconnect() {
        window.removeEventListener('turbo:frame-load', () => this.checkIfEmpty())
    }

    toggle(): void {
        if (this.hasPanelTarget) {
            this.element.classList.toggle(this.collapsedClass);
        }
    }

    private checkIfEmpty(): void {
        console.log('check if empty')
        if (!this.hasPanelTarget) return;

        const turboFrame = this.panelTarget.querySelector('turbo-frame');

        if (!turboFrame || turboFrame.innerHTML.trim() === '') {
            this.element.classList.add('d-none');
        } else {
            this.element.classList.remove('d-none');
        }
    }
}

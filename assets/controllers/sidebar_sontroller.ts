import { Controller } from '@hotwired/stimulus';

export default class extends Controller<HTMLElement> {
    static classes = ['collapsed'];
    static targets = ['panel', "button"];

    declare readonly collapsedClass: string;
    declare readonly panelTarget: HTMLDivElement;
    declare readonly buttonTarget: HTMLButtonElement;
    declare readonly hasPanelTarget: boolean;

    connect() {
        this.buttonTarget.addEventListener('click', () => this.toggle());
        console.log('toto')
        // wait to be sure turbo has time to do his stuff
        setTimeout(() => this.checkIfEmpty(), 0);
    }

    private toggle(): void {
        if (this.hasPanelTarget) {
            this.element.classList.toggle(this.collapsedClass);
        }
    }

    private checkIfEmpty(): void {
        if (!this.hasPanelTarget) return;

        const turboFrame = this.panelTarget.querySelector('turbo-frame');

        if (!turboFrame || turboFrame.innerHTML.trim() === '') {
            this.element.classList.add('d-none');
        } else {
            this.element.classList.remove('d-none');
        }
    }
}

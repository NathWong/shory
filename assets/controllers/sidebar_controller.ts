import {Controller} from '@hotwired/stimulus';

export default class extends Controller<HTMLElement> {
    static targets = ['panel'];

    private readonly collapsedClass: string = 'sidebar--collapsed';
    declare readonly panelTarget: HTMLDivElement;
    declare readonly hasPanelTarget: boolean;

    connect() {
        this.element.addEventListener('sidebar-content:open', () => this.open())
        window.addEventListener('check', () => this.checkIfEmpty())
        // wait to be sure turbo has time to do his stuff
        setTimeout(() => this.checkIfEmpty(), 0);
    }

    disconnect() {
        this.element.removeEventListener('sidebar-content:open', () => this.open())
        this.element.removeEventListener('sidebar-content:close', () => this.close())
    }

    toggle(): void {
        if (this.hasPanelTarget) {
            this.element.classList.toggle(this.collapsedClass);
        }
    }

    private checkIfEmpty(): void {
        if (!this.hasPanelTarget) return;

        const turboFrame = this.panelTarget.querySelector('turbo-frame');

        if (!turboFrame || turboFrame.innerHTML.trim() === '') {
            this.close();
        } else {
            this.open();
        }
    }

    private open(): void
    {
        this.element.classList.remove('d-none');
    }

    private close(): void {
        this.element.classList.add('d-none');
    }
}

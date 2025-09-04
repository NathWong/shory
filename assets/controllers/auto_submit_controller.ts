import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLFormElement> {
    private debouncerTimer: number | undefined;

    connect() {
        const focusElementId = sessionStorage.getItem('autofocus_id');
        const cursorPosition = parseInt(sessionStorage.getItem('cursor_position') || '0', 10)

        this.focusElement(focusElementId, cursorPosition);

        sessionStorage.removeItem('autofocus_id')
        sessionStorage.removeItem('cursor_position')
    }

    submit(event: Event): void {
        if (this.debouncerTimer) {
            clearTimeout(this.debouncerTimer);
        }

        const target = event.target as HTMLInputElement
        sessionStorage.setItem('autofocus_id', target.id);
        sessionStorage.setItem('cursor_position', (target.selectionStart || 0).toString());

        this.debouncerTimer = window.setTimeout(() => {
            this.element.requestSubmit();
        }, 500)
    }

    private focusElement(focusedElementId: string | null | undefined, cursorPosition: number): void {
        if (!focusedElementId) {
            return;
        }

        const elementToFocus = this.element.querySelector<HTMLInputElement | HTMLSelectElement>(`#${focusedElementId}`)

        if (!elementToFocus) {
            return;
        }

        elementToFocus.focus();

        if (elementToFocus.tagName !== 'INPUT') {
            return;
        }

        (elementToFocus as HTMLInputElement).setSelectionRange(cursorPosition, cursorPosition);
    }
}

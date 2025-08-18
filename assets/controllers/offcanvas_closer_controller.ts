import { Controller } from '@hotwired/stimulus';
import { Offcanvas } from 'bootstrap';

export default class extends Controller<HTMLDivElement> {

    close(): void {
        const instance = Offcanvas.getInstance(this.element);

        if (instance) {
            instance.hide();
        }
    }
}

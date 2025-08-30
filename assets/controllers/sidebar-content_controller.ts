import {Controller} from '@hotwired/stimulus';

export default class extends Controller<HTMLElement> {
    connect() {
        this.dispatch('open', {bubbles: true});
    }

    disconnect() {
        window.dispatchEvent(new Event('check'));
    }
}

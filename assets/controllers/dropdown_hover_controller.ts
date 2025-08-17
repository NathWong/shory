import { Controller } from '@hotwired/stimulus';
import { Dropdown } from 'bootstrap';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLDivElement> {
    static targets = ['toggle']

    declare readonly toggleTarget : HTMLAnchorElement

    connect() {
        const dropdown = new Dropdown(this.toggleTarget);
        dropdown.hide();

        this.element.addEventListener('mouseenter', () => dropdown.show());
        this.element.addEventListener('mouseleave', () => dropdown.hide());
    }
}

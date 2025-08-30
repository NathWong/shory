import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['collectionContainer']

    declare readonly collectionContainerTarget: HTMLElement

    add(event: Event): void {
        event.preventDefault();

        const prototype = this.collectionContainerTarget.dataset.prototype;
        if (!prototype) {
            console.error('No prototype found in collectionContainer dataset')

            return;
        }
        const count = this.collectionContainerTarget.childElementCount;

        const newForm = prototype.replace(/__name__/g, count.toString());

        this.collectionContainerTarget.insertAdjacentHTML('beforeend', newForm);
    }

    remove(event: Event): void {
        event.preventDefault();

        const item = (event.currentTarget as HTMLElement).closest('[data-form-collection-item]');
        if (item) {
            item.remove();
        }
    }
}

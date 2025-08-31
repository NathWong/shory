import { Controller } from '@hotwired/stimulus';
import {Tooltip} from "bootstrap";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLElement> {

    connect() {
        new Tooltip(this.element)
    }
}

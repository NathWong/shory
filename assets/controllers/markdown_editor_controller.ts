import { Controller } from '@hotwired/stimulus';
import { marked } from 'marked';
import DOMPurify from 'dompurify';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['source', 'preview', 'editButton', 'previewButton']

    declare readonly sourceTarget: HTMLTextAreaElement;
    declare readonly previewTarget: HTMLElement;
    declare readonly editButtonTarget: HTMLButtonElement;
    declare readonly previewButtonTarget: HTMLButtonElement;

    connect() {
        this.edit();
    }

    preview(): void {
        this.sourceTarget.style.display = 'none';
        this.previewTarget.style.display = 'block';

        const rawHtml = <string>marked(this.sourceTarget.value);
        this.previewTarget.innerHTML = DOMPurify.sanitize(rawHtml, {
            ALLOWED_TAGS: [
                'h1', 'h2', 'h3', 'ul', 'ol', 'li',
                'strong', 'em', 'b', 'i', 'p', 'br'
            ],
            ALLOWED_ATTR: []
        });

        this.previewButtonTarget.classList.add('active');
        this.editButtonTarget.classList.remove('active');
    }

    edit(): void {
        this.sourceTarget.style.display = 'block';
        this.previewTarget.style.display = 'none';
        this.sourceTarget.focus();

        this.previewButtonTarget.classList.remove('active');
        this.editButtonTarget.classList.add('active');
    }

    wrapSelection(event: Event): void {
        event.preventDefault();

        const button = event.currentTarget as HTMLButtonElement;
        const pattern = button.dataset.pattern || '%%text%%';

        const start = this.sourceTarget.selectionStart;
        const end = this.sourceTarget.selectionEnd;
        const selectedText = this.sourceTarget.value.substring(start, end);

        const newText = pattern.replace('%%text%%', selectedText);
        const cursorPos = newText.indexOf('%%text%%') > -1 ? newText.indexOf('%%text%%') : newText.length;

        this.sourceTarget.setRangeText(newText, start, end, 'select');
        this.sourceTarget.focus();
        this.sourceTarget.selectionStart = start + cursorPos;
        this.sourceTarget.selectionEnd = start + cursorPos + selectedText.length;
    }
}

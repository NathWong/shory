import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://symfony.com/bundles/StimulusBundle/current/index.html#lazy-stimulus-controllers
*/

/* stimulusFetch: 'lazy' */
export default class extends Controller<HTMLTextAreaElement> {

    connect() {
    // if (CKEDITOR.instances["chapter_content"]) {
    //     CKEDITOR.instances["chapter_content"].destroy(true);
    //     delete CKEDITOR.instances["chapter_content"];
    // }
    //
    //     CKEDITOR.replace("chapter_content",
    //         {
    //             "toolbar": [["Bold", "Italic", "Underline", "-", "Link", "Unlink", "-", "Image"],
    //                 ["JustifyLeft", "JustifyCenter", "JustifyRight", "-", "Font", "FontSize"]],
    //             "font_names": "Arial\/Arial, Helvetica, sans-serif;Times New Roman\/Times New Roman, Times, serif;Verdana\/Verdana, Geneva, sans-serif;Comic Sans MS\/Comic Sans MS, cursive;Courier New\/Courier New, Courier, monospace",
    //             "language": "en"
    //         });


    }

    disconnect() {
    /* @hack to prevent ckeditor initialization problems */
        // if (CKEDITOR.instances["ter_content"]) {
        //     CKEDITOR.instances["ter_content"].destroy();
        // }
    }
}

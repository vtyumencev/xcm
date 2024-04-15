import Storage from "./types/Storage";

const Blocker = () => {

    let appStorage: Storage;

    const updateReplacers = () => {
        document.querySelectorAll('.xcm-embed-replacer').forEach((element) => {
            if (!appStorage.isProviderBlocked((element.getAttribute('data-provider')))) {
                const node = fromHTML(element.getAttribute('data-origin'));
                nodeScriptReplace(node);
                element.after(node);
                element.remove();
            }
        });
    }

    return {
        start(storage: Storage) {
            appStorage = storage;

            document.addEventListener('DOMContentLoaded', () => {
                updateReplacers();
            });

            appStorage.on('contestUpdated', () => {
                updateReplacers();
            })
        }
    }
}

function fromHTML (html: string, trim = true): Element {
    // Process the HTML string.
    html = trim ? html.trim() : html;
    if (!html) return null;

    // Then set up a new template element.
    const template = document.createElement('template');
    template.innerHTML = html;
    const result = template.content.children;

    return result[0];
}
function nodeScriptReplace(node: Element) {
    if ( nodeScriptIs(node) === true ) {
        node.parentNode.replaceChild( nodeScriptClone(node) , node );
    }
    else {
        let i = -1, children = node.childNodes as NodeListOf<Element>;
        while ( ++i < children.length ) {
            nodeScriptReplace( children[i] );
        }
    }

    return node;
}
function nodeScriptClone(node: Element){
    const script  = document.createElement("script");
    script.text = node.innerHTML;

    let i = -1, attrs = node.attributes, attr: Attr;
    while ( ++i < attrs.length ) {
        script.setAttribute( (attr = attrs[i]).name, attr.value );
    }
    return script;
}

function nodeScriptIs(node: Element) {
    return node.tagName === 'SCRIPT';
}


export default Blocker();
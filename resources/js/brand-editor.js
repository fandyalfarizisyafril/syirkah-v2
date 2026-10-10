import { createIcons, Plus, Trash2 } from 'lucide';

const editor = document.querySelector('[data-brand-technologies]');
if (editor) {
    const rows = editor.querySelector('[data-technology-rows]');
    const add = editor.querySelector('[data-add-technology]');
    let index = Math.max(-1, ...Array.from(rows.querySelectorAll('input'), input => Number(input.name.match(/\[(\d+)\]/)?.[1] ?? -1))) + 1;
    const sync = () => { add.disabled = rows.children.length >= 12; };
    add.addEventListener('click', () => {
        if (rows.children.length >= 12) return;
        const row = editor.querySelector('template').content.firstElementChild.cloneNode(true);
        row.querySelectorAll('[data-technology-field]').forEach(field => {
            const key = field.dataset.technologyField;
            field.id = `technology-${key}-${index}`;
            field.name = `technology_details[${index}][${key}]`;
            row.querySelector(`[data-technology-label="${key}"]`).htmlFor = field.id;
        });
        rows.append(row);
        index++;
        createIcons({ icons: { Plus, Trash2 }, root: editor });
        sync();
        row.querySelector('input').focus();
    });
    rows.addEventListener('click', event => {
        const button = event.target.closest('[data-remove-technology]');
        if (!button) return;
        button.closest('[data-technology-row]').remove();
        sync();
        add.focus();
    });
    sync();
}

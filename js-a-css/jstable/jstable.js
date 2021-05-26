class JsTable {
    constructor(dataCollection, HTMLElement) {
        this.dataCollection = dataCollection;
        this.HTMLElement = HTMLElement;

        this.renderTable();
    }
    renderTable(){
        let header = this.renderHeader();
        let body = this.renderRows();
        let table = `<table>${header}${body}</table>`;
        this.HTMLElement.innerHTML = table;
    }
    renderHeader(){
        return `<tr><td>body</td></tr>`
    }
    renderRows(){
        return `<tr><th>header</th></tr>`
    }
}
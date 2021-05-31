class JsTable {
    constructor(dataCollection, HTMLElement) {
        this.dataCollection = dataCollection;
        this.HTMLElement = HTMLElement;

        this.lastSortedBy = null;

        this.renderTable();
    }
    renderTable(){
        this.HTMLElement.innerHTML = "";
        let header = this.renderHeader();
        let body = this.renderRows();
        let table = `<table border="1">${body}</table>`;
        this.HTMLElement.innerHTML = table;
        this.HTMLElement.querySelector("table").prepend(header);
    }

    sortCollection(filterBy){

        if (this.lastSortedBy == null && this.lastSortedBy != filterBy) {
            this.dataCollection.sort(function (a,b){
                return String(a[filterBy]).localeCompare(String(b[filterBy]));
            });
            this.lastSortedBy = filterBy;
        } else {
            this.dataCollection.sort(function (a,b){
                return String(b[filterBy]).localeCompare(String(a[filterBy]));
            });
            this.lastSortedBy = null;
        }

        this.renderTable();
    }

    renderHeader(){
        let headerRow = document.createElement("tr");
        let firstItem = this.dataCollection[0];
        Object.keys(firstItem).forEach((attributeName,i) => {
            let hr = document.createElement("th");
            hr.innerText = attributeName;
            hr.style.cursor = "pointer";
            hr.onclick = () => {
                this.sortCollection(attributeName);
            }
            headerRow.appendChild(hr);
        });

        return headerRow;
    }

    renderHeaderOld(){
        let firstItem = this.dataCollection[0];
        let headerText = "";
        Object.keys(firstItem).forEach((attributeName,i) => {
            headerText += `<th>${attributeName}</th>`
        });
        return `<tr>${headerText}</tr>`
    }
    renderRows(){
        let bodyText = "";
        let keys = Object.keys(this.dataCollection[0]);
        this.dataCollection.forEach( (item, i) => {
            let rowText = "";
            keys.forEach((attributeName,i) => {
                rowText += `<td>${item[attributeName]}</td>`
            });
            bodyText += `<tr>${rowText}</tr>`
        } )
        return bodyText;
    }
}
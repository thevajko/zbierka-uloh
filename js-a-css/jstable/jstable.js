class JsTable {
    constructor(dataCollection, HTMLElement) {

        this.dataCollection = dataCollection;
        this.filtedDataCollection = dataCollection;

        this.HTMLElement = HTMLElement;

        this.TableWrapperElement = document.createElement('div');
        this.HTMLElement.append(this.TableWrapperElement);

        let input = document.createElement("input");
        input.oninput = (event) => {
            this.filterCollection(event.target.value);
        }

        this.HTMLElement.prepend(input);

        this.lastSortedBy = null;

        this.renderTable();
    }

    renderTable(){
        this.TableWrapperElement.innerHTML = "";
        let body = this.renderRows();
        let table = `<table border="1">${body}</table>`;
        this.TableWrapperElement.innerHTML = table;
        this.TableWrapperElement.querySelector("table").prepend(this.renderHeader());
    }

    sortCollection(filterBy){
        if (this.lastSortedBy == null && this.lastSortedBy != filterBy) {
            this.filtedDataCollection.sort(function (a,b){
                return String(a[filterBy]).localeCompare(String(b[filterBy]));
            });
            this.lastSortedBy = filterBy;
        } else {
            this.filtedDataCollection.sort(function (a,b){
                return String(b[filterBy]).localeCompare(String(a[filterBy]));
            });
            this.lastSortedBy = null;
        }

        this.renderTable();
    }

    filterCollection(expression){
        if (expression == null || expression.length < 2) {
            this.filtedDataCollection = this.dataCollection;
        } else {
            let keys =  Object.keys(this.dataCollection[0]);
            this.filtedDataCollection = this.dataCollection.filter((a) => {
                for (let i= 0;  i < keys.length; i++) {
                    if (String(a[keys[i]]).includes(expression)) {
                        return true;
                    }
                }
                return false;
            });
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

    renderRows(){
        let bodyText = "";
        let keys = Object.keys(this.dataCollection[0]);
        this.filtedDataCollection.forEach( (item, i) => {
            let rowText = "";
            keys.forEach((attributeName,i) => {
                rowText += `<td>${item[attributeName]}</td>`
            });
            bodyText += `<tr>${rowText}</tr>`
        } )
        return bodyText;
    }
}
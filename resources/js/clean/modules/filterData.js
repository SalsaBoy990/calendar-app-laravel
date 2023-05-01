/*
*  Filter / search in table items action
*
* */
export function filterData() {

    return {
        filterTerm: '',
        dataType: 'table',
        sourceId: 'filter-table',

        filter() {
            switch (this.dataType) {
                case 'table':
                    let td;
                    const table = document.getElementById(this.sourceId);
                    const tr = table.getElementsByTagName('tr');

                    for (let i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName('td')[0];
                        if (td) {
                            if (td.innerHTML.toUpperCase().indexOf(this.filterTerm.toUpperCase()) > -1) {
                                tr[i].style.display = '';
                            } else {
                                tr[i].style.display = 'none';
                            }
                        }
                    }
                    break;
                case 'list':
                    const list = document.getElementById(this.sourceId);
                    const li = list.getElementsByTagName('li');

                    for (let i = 0; i < li.length; i++) {
                        if (li[i].innerHTML.toUpperCase().indexOf(this.filterTerm.toUpperCase()) > -1) {
                            li[i].style.display = '';
                        } else {
                            li[i].style.display = 'none';
                        }
                    }
                    break;
                default:
                    break;
            }
        },

    }
}

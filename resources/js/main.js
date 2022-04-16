const { create } = require("lodash");

/**
* Template Name: NiceAdmin - v2.2.2
* Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
(function() {
  "use strict";

  /**
   * Easy selector helper function
   */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /**
   * Easy event listener function
   */
  const on = (type, el, listener, all = false) => {
    if (all) {
      select(el, all).forEach(e => e.addEventListener(type, listener))
    } else {
      select(el, all).addEventListener(type, listener)
    }
  }

  /**
   * Easy on scroll event listener
   */
  const onscroll = (el, listener) => {
    el.addEventListener('scroll', listener)
  }

  /**
   * Sidebar toggle
   */
  if (select('.toggle-sidebar-btn')) {
    on('click', '.toggle-sidebar-btn', function(e) {
      select('body').classList.toggle('toggle-sidebar')
    })
  }

  /**
   * Search bar toggle
   */
  if (select('.search-bar-toggle')) {
    on('click', '.search-bar-toggle', function(e) {
      select('.search-bar').classList.toggle('search-bar-show')
    })
  }

  /**
   * Navbar links active state on scroll
   */
  let navbarlinks = select('#navbar .scrollto', true)
  const navbarlinksActive = () => {
    let position = window.scrollY + 200
    navbarlinks.forEach(navbarlink => {
      if (!navbarlink.hash) return
      let section = select(navbarlink.hash)
      if (!section) return
      if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
        navbarlink.classList.add('active')
      } else {
        navbarlink.classList.remove('active')
      }
    })
  }
  window.addEventListener('load', navbarlinksActive)
  onscroll(document, navbarlinksActive)

  /**
   * Toggle .header-scrolled class to #header when page is scrolled
   */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      if (window.scrollY > 100) {
        selectHeader.classList.add('header-scrolled')
      } else {
        selectHeader.classList.remove('header-scrolled')
      }
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /**
   * Back to top button
   */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      if (window.scrollY > 100) {
        backtotop.classList.add('active')
      } else {
        backtotop.classList.remove('active')
      }
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /**
   * Initiate tooltips
   */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /**
   * Initiate quill editors
   */
  if (select('.quill-editor-default')) {
    new Quill('.quill-editor-default', {
      theme: 'snow'
    });
  }

  if (select('.quill-editor-bubble')) {
    new Quill('.quill-editor-bubble', {
      theme: 'bubble'
    });
  }

  if (select('.quill-editor-full')) {
    new Quill(".quill-editor-full", {
      modules: {
        toolbar: [
          [{
            font: []
          }, {
            size: []
          }],
          ["bold", "italic", "underline", "strike"],
          [{
              color: []
            },
            {
              background: []
            }
          ],
          [{
              script: "super"
            },
            {
              script: "sub"
            }
          ],
          [{
              list: "ordered"
            },
            {
              list: "bullet"
            },
            {
              indent: "-1"
            },
            {
              indent: "+1"
            }
          ],
          ["direction", {
            align: []
          }],
          ["link", "image", "video"],
          ["clean"]
        ]
      },
      theme: "snow"
    });
  }

  /**
   * Initiate Bootstrap validation check
   */
  var needsValidation = document.querySelectorAll('.needs-validation')

  Array.prototype.slice.call(needsValidation)
    .forEach(function(form) {
      form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })

  /**
   * Initiate Datatables
   */
  const datatables = select('.datatable', true)
  datatables.forEach(datatable => {
    new simpleDatatables.DataTable(datatable);
  })

  /**
   * Autoresize echart charts
   */
  const mainContainer = select('#main');
  if (mainContainer) {
    setTimeout(() => {
      new ResizeObserver(function() {
        select('.echart', true).forEach(getEchart => {
          echarts.getInstanceByDom(getEchart).resize();
        })
      }).observe(mainContainer);
    }, 200);
  }

})();

const processHolder = document.getElementById('processHolder') || null;
const addProcessBtn = document.getElementById('addProcess') || null;
let NoP = 0;
if(addProcessBtn !== null) {
    const getMaintenances = async () => {
        let gotData = null;
        $.ajax({
            type:'GET',
            url:'/ajax/maintenances',
            success:function(data) {
                gotData = data;
            }
         });
         return gotData;
    }
    let _MAINTENANCES = null;
    window.addEventListener('load', async () => {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            },
            async: false
        });
        _MAINTENANCES = await getMaintenances();

    });


    const generateMaintenanceSelect = (object, nop) => {
        let html = "<div class='col-12 col-md-4 mb-2'><select name='process["+nop+"][maintenance]' class='form-select' id='maintenance_id'>";
        object.forEach((m, i) => {
            html += `<option ${(i === 0) ? 'selected' : '' } value=${m.id}>${m.name}</option>`;
        });
        html += "</select></div>";
        return html;
    }
    const generateTextInput = (inputname, placeholder, cols, nop, type, required=true) => {
        const req = (required) ? 'required' : '';
        switch(type) {
            case 'text':
                return (
                    "<div class='"+cols+"'>"
                        +"<input type='text' name='process["+nop+"]["+inputname+"]' class='form-control mb-2' placeholder='"+(placeholder)+"' "+(req)+" />"
                    +"</div>"
                );
            case 'textarea':
                return (
                    "<div class='"+cols+"'>"
                        +"<textarea name='process["+nop+"]["+inputname+"]' class='form-control mb-2' style='height: 100px' placeholder='"+(placeholder)+"' "+(req)+"></textarea>"
                    +"</div>"
                );
        }
    }

    //PROCESS VALIDATION ERROR NEM JELENIK MEG nem bajaz
    const genenerateHTML = (type, nop) => {
        switch(type){
            case 1:
              return (
                  generateMaintenanceSelect(_MAINTENANCES, nop) +""+
                  generateTextInput("time_span", "Időtartam(h)", "col-12 col-md-4", nop, 'text') +""+
                  generateTextInput("price", "Ár(ft)", "col-12 col-md-4", nop, 'text') +""+
                  addSelfDelete(nop)
              );
            case 2:
              return (
                  generateTextInput("name", "Anyag neve", "col-12", nop, 'text') +""+
                  generateTextInput("amount", "Mennyiség (db)", "col-6", nop, 'text') +""+
                  generateTextInput("price", "Ár(ft)", "col-6", nop, 'text') +""+
                  addSelfDelete(nop)
              );
            case 3:
              return (
                  generateTextInput("name", "Alkatrész neve", "col-12 col-md-6", nop, 'text') +""+
                  generateTextInput("serial", "Alkatrész sorozatszáma", "col-12 col-md-6", nop, 'text') +""+
                  generateTextInput("amount", "Mennyiség (db)", "col-6", nop, 'text') +""+
                  generateTextInput("price", "Ár(ft)", "col-6", nop, 'text') +""+
                  addSelfDelete(nop)
              );
            case 4:
              return (
                  generateTextInput("name", "Munkafolyamat megnevezése", "col-12", nop, 'text') +""+
                  generateTextInput("info", "Leírás (nem kötelező)", "col-12", nop, 'textarea', false) +""+
                  generateTextInput("time_span", "Időtartam (h)", "col-6", nop, 'text') +""+
                  generateTextInput("price", "Ár(ft)", "col-6", nop, 'text') +""+
                  addSelfDelete(nop)
              );
        }
    }
    const deleteSelfAction = (e) => {
        e.target.parentElement.parentElement.remove();
        NoP--;
    }

    const addSelfDelete = (nop) => {
        const btn = "<button class='mt-2 col-12 btn btn-danger' id='self_delete_"+nop+"'>Elem törlése</button>";
        console.log(btn)
        return btn;
    }


    const changeProcessInputs = (e) => {
        const id = parseInt(e.target.getAttribute('id'));
        const type = parseInt(e.target.value);

        document.getElementById('input_group_' + id).innerHTML = genenerateHTML(type, id);
        document.getElementById("self_delete_"+NoP).addEventListener('click', (e) => {
            e.preventDefault();
            deleteSelfAction(e);
        });
    }

    const createProcess = (type) => {
        NoP++;
        let HTML =
            "<div class='col-12 col-md-6 mb-2'>"
                +"<select name='process["+NoP+"][process]' class='form-select process_selector' id='"+NoP+"'>"
                            +"<option selected value='1'>Általános</option>"
                            +"<option value='2'>Anyag</option>"
                            +"<option value='3'>Alkatrész</option>"
                            +"<option value='4'>Egyéni</option>"
                    +"</select>"
            +"</div>"
        ;

        HTML += "<div id='input_group_"+NoP+"' class='row p-0'>"+genenerateHTML(type, NoP)+"</span>";
        const inputs = document.createElement("li");
        inputs.className = "list-group-item d-flex justify-content-evenly align-items-start row m-0 p-2";
        inputs.setAttribute('id', "process-" + NoP);
        inputs.innerHTML = HTML;
        processHolder.append(inputs);
        document.getElementById(NoP).addEventListener('change', (e) => {
            changeProcessInputs(e);
        });
        document.getElementById("self_delete_"+NoP).addEventListener('click', (e) => {
            e.preventDefault();
            deleteSelfAction(e);
        });

    }
    addProcessBtn.addEventListener('click', (e) => {
        e.preventDefault();
        createProcess(1);
    });

}
if(location.pathname.split('/')[1] === 'worksheets' && (location.pathname.split('/')[2] === null || location.pathname.split('/')[2] === undefined)){
    const redirectWithParams = (param) => {
        const url = new URL(window.location.href);
        console.log(param.value)
        if(param.value !== null || param.value === '' || param.value === undefined) {
            url.searchParams.set(param.name, param.value);
            document.location.replace(url.href)
        }else{
            url.searchParams.delete(param.name);
            document.location.replace(url.href)
        }

    }

    document.getElementById('filter_closed').addEventListener('change', (e) => {
        let params = {name: 'closed', value: null};

        if(e.target.value === '1') params.value = 'false';
        if(e.target.value === '2') params.value = 'true';
        redirectWithParams(params)
    });

    document.getElementById('filter_date').addEventListener('change', (e) => {
        let params = {name: 'date', value: null};

        if(e.target.value === '0') params.value = 'desc';
        if(e.target.value === '1') params.value = 'asc';
        redirectWithParams(params)
    });

    document.getElementById('filter_search_btn').addEventListener('click', (e) => {
        let params = {name: 'search', value: null};
        params.value = document.getElementById('search').value || null;
        redirectWithParams(params)
    });
}


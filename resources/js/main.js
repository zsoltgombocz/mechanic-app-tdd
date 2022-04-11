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

const processHolder = document.getElementById('processHolder');
const addProcessBtn = document.getElementById('addProcess');
let NoP = 0;

const getMaintenances = async () => {
    console.log('getting');
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


const generateMaintenanceSelect = (object) => {
    let html = "<div class='col-4'><select name='process["+NoP+"][maintenance]' class='form-select' id='maintenance_id'>";
    object.forEach((m, i) => {
        html += `<option ${(i === 0) ? 'selected' : '' } value=${m.id}>${m.name}</option>`;
    });
    html += "</select></div>";
    return html;
}
const generateTimeSpanInput = () => {
    return (
        "<div class='col-2'>"
            +"<input type='text' name='process["+NoP+"][time_span]' class='form-control' id='time_span' placeholder='Idő' required/>"
        +"</div>"
    );
}
const generatePriceInput = () => {
    return (
        "<div class='col-2'>"
            +"<input type='text' name='process["+NoP+"][price]' class='form-control' id='price' placeholder='Ár' required/>"
        +"</div>"
    );
}
//PROCESS VALIDATION ERROR NEM JELENIK MEG
const genenerateHTML = (type) => {
    switch(type){
        case 1:
            return (
                generateMaintenanceSelect(_MAINTENANCES) +""+
                generateTimeSpanInput() +""+
                generatePriceInput()
            );
    }
}
const createProcess = () => {
    NoP++;
    let HTML =
        "<div class='col-4'>"
            +"<select name='process["+NoP+"][process]' class='form-select' id='process_id'>"
                        +"<option selected value='1'>Általános</option>"
                        +"<option value='2'>Anyag</option>"
                        +"<option value='3'>Alkatrész</option>"
                        +"<option value='4'>Egyéni</option>"
                +"</select>"
        +"</div>"
    ;

    HTML += genenerateHTML(1);
    HTML +=  "<div class='invalid-feedback'>A mezők kitöltése kötelező!</div>";
    const inputs = document.createElement("div");
    inputs.className = "row mb-2 has-validation input-group";
    inputs.setAttribute('id', "process-" + NoP);
    inputs.innerHTML = HTML;
    processHolder.append(inputs);

}
addProcessBtn.addEventListener('click', (e) => {
    e.preventDefault();
    createProcess();
});

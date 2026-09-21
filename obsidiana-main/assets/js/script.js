document.addEventListener('DOMContentLoaded', function () {
    initAvatarMenu();
    initSidebarToggle();
    initSenhaValidacao();
    initFiltroLista();
    initSelecaoCliente();
    initCalendario();
});

/* ---------- Menu do avatar (topo direito) ---------- */
function initAvatarMenu() {
    var wrapper = document.getElementById('avatarWrapper');
    if (!wrapper) return;
    var menu = document.getElementById('avatarMenu');

    wrapper.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('aberto');
    });
    document.addEventListener('click', function () {
        menu.classList.remove('aberto');
    });
}

/* ---------- Recolher/expandir sidebar de ícones ---------- */
function initSidebarToggle() {
    var btn = document.getElementById('btnToggleSidebar');
    var sidebar = document.getElementById('sidebar');
    if (!btn || !sidebar) return;

    btn.addEventListener('click', function () {
        sidebar.classList.toggle('recolhida');
    });
}

/* ---------- Confirmação de senha nos formulários de cadastro ---------- */
function initSenhaValidacao() {
    var form = document.getElementById('formCadastro');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        var senha = form.querySelector('[name="senha"]').value;
        var confirmar = form.querySelector('[name="confirmar_senha"]').value;

        if (senha !== confirmar) {
            e.preventDefault();
            alert('As senhas informadas não coincidem.');
        }
    });
}

/* ---------- Filtro de busca em listas (clientes, pedidos, calendário) ---------- */
function initFiltroLista() {
    document.querySelectorAll('[data-filtro-lista]').forEach(function (input) {
        var alvoSeletor = input.getAttribute('data-filtro-lista');
        var itens = document.querySelectorAll(alvoSeletor);

        input.addEventListener('input', function () {
            var termo = input.value.trim().toLowerCase();
            itens.forEach(function (item) {
                var texto = item.textContent.toLowerCase();
                item.style.display = texto.indexOf(termo) !== -1 ? '' : 'none';
            });
        });
    });
}

/* ---------- Seleção visual de cliente na lista lateral ---------- */
function initSelecaoCliente() {
    var pills = document.querySelectorAll('.cliente-pill');
    if (!pills.length) return;

    pills.forEach(function (pill) {
        pill.addEventListener('click', function () {
            pills.forEach(function (p) { p.classList.remove('selecionado'); });
            pill.classList.add('selecionado');

            var painel = document.getElementById('detalheCliente');
            if (painel) {
                painel.innerHTML = '<h3>' + pill.dataset.nome + '</h3><p>Cliente selecionado. Utilize o menu lateral para criar um novo pedido para este cliente.</p>';
            }
        });
    });
}

/* ---------- Calendário interativo (Frame 9) ---------- */
function initCalendario() {
    var grade = document.getElementById('gradeCalendario');
    if (!grade) return;

    var selectMes = document.getElementById('selectMes');
    var selectAno = document.getElementById('selectAno');
    var btnAnterior = document.getElementById('btnMesAnterior');
    var btnProximo = document.getElementById('btnMesProximo');

    // Eventos (dias com pedidos) injetados pelo PHP via atributo data-eventos no elemento da grade
    var eventos = {};
    try {
        eventos = JSON.parse(grade.dataset.eventos || '{}');
    } catch (err) {
        eventos = {};
    }

    var hoje = new Date();
    var mesAtual = parseInt(selectMes.value, 10);
    var anoAtual = parseInt(selectAno.value, 10);

    function renderCalendario(mes, ano) {
        grade.innerHTML = '';

        var diasSemana = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        diasSemana.forEach(function (d) {
            var el = document.createElement('div');
            el.className = 'dia-semana';
            el.textContent = d;
            grade.appendChild(el);
        });

        var primeiroDia = new Date(ano, mes, 1);
        var ultimoDia = new Date(ano, mes + 1, 0);
        var offset = primeiroDia.getDay();

        // dias do mês anterior (preenchimento)
        var ultimoDiaMesAnterior = new Date(ano, mes, 0).getDate();
        for (var i = offset; i > 0; i--) {
            grade.appendChild(criarCelulaDia(ultimoDiaMesAnterior - i + 1, true));
        }

        for (var dia = 1; dia <= ultimoDia.getDate(); dia++) {
            var ehHoje = (dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear());
            var chave = ano + '-' + String(mes + 1).padStart(2, '0') + '-' + String(dia).padStart(2, '0');
            grade.appendChild(criarCelulaDia(dia, false, ehHoje, eventos[chave]));
        }

        var totalCelulas = offset + ultimoDia.getDate();
        var restante = (7 - (totalCelulas % 7)) % 7;
        for (var j = 1; j <= restante; j++) {
            grade.appendChild(criarCelulaDia(j, true));
        }
    }

    function criarCelulaDia(numero, outroMes, ehHoje, corEvento) {
        var el = document.createElement('div');
        el.className = 'dia' + (outroMes ? ' outro-mes' : '') + (ehHoje ? ' hoje' : '');
        el.textContent = numero;

        if (corEvento) {
            var dot = document.createElement('span');
            dot.className = 'evento-dot';
            dot.style.background = corEvento;
            el.appendChild(dot);
        }
        return el;
    }

    selectMes.addEventListener('change', function () {
        mesAtual = parseInt(selectMes.value, 10);
        renderCalendario(mesAtual, anoAtual);
    });
    selectAno.addEventListener('change', function () {
        anoAtual = parseInt(selectAno.value, 10);
        renderCalendario(mesAtual, anoAtual);
    });
    btnAnterior.addEventListener('click', function () {
        mesAtual--;
        if (mesAtual < 0) { mesAtual = 11; anoAtual--; selectAno.value = anoAtual; }
        selectMes.value = mesAtual;
        renderCalendario(mesAtual, anoAtual);
    });
    btnProximo.addEventListener('click', function () {
        mesAtual++;
        if (mesAtual > 11) { mesAtual = 0; anoAtual++; selectAno.value = anoAtual; }
        selectMes.value = mesAtual;
        renderCalendario(mesAtual, anoAtual);
    });

    renderCalendario(mesAtual, anoAtual);
}

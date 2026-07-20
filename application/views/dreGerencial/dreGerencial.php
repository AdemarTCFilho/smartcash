
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width,initial-scale=1">
      <title>DRE Gerencial</title>
      <style>
         body {
	margin: 0;
	background: #06081b;
	color: #fff;
	font-family: Arial, sans-serif
}

.container {
	max-width: 1600px;
	margin: auto;
	padding: 16px
}

.top {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	margin-bottom: 16px
}

h1 {
	margin: 0;
	font-size: 34px
    color: #fff;
}

.sub {
	color: #a7afd8;
	font-size: 13px
}

select,
    button {
        background: #14183c;
        color: #fff;
        border: 1px solid #323a88;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 12px;
    }

.btn {
        background: #11183D;
        border: 1px solid var(--border);
        color: #8E98C6;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn:hover {
        background: #121f5e;
        border: 1px solid var(--border);
        color: #8E98C6;
        padding: 8px 14px;
        border-radius: 8px;
        cursor: pointer;
    }

.notice,
.panel,
.card {
	background: #0b1030;
	border: 1px solid #2b3473;
	border-radius: 10px
}

.notice {
	padding: 12px;
	color: #b8c0ef;
	margin: 16px 0
}

.cards {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 14px
}

.card {
	padding: 18px
}

.label {
	font-size: 11px;
	color: #98a4e6;
	text-transform: uppercase;
    background: #0b1030 !important;
}

.value {
	font-size: 34px;
	font-weight: bold;
	margin-top: 8px
}

.green {
	color: #40e27d
}

.red {
	color: #ff5e5e
}

.branco {
    color: #fff;
}

.panel {
	margin-top: 18px;
	overflow: hidden
}

.panel h3 {
	margin: 0;
	padding: 14px 16px;
	border-bottom: 1px solid #2b3473;
	font-size: 16px
}

.row {
	display: flex;
	justify-content: space-between;
	padding: 14px 16px;
	border-top: 1px solid #242d66
}

.row > strong:last-child,
.row > span:last-child {
	text-align: right;
	margin-left: auto;
}

.muted {
	color: #9aa5dc
}

.grid2 {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 16px;
	margin-top: 18px
}

.card-border-red {
    border: 0.5px solid #ff5e5e62;
    border-radius: 10px
}

.card-border-green {
    border: 1px solid #40e27e69;
    border-radius: 10px
}

table {
	width: 100%;
	border-collapse: collapse
}

th,
td {
	padding: 10px 12px;
	border-top: 1px solid #242d66
}

th {
	text-align: left;
	color: #99a4df;
	font-size: 12px
}

.title {
            font-size: 34px;
            font-weight: 700;
            margin-top: 5px;
        }

@media(max-width:900px) {

	.cards,
	.grid2 {
		grid-template-columns: 1fr
	}

	.top {
		flex-direction: column;
		gap: 12px
	}
}
      </style>

      <div class="container">
         <div class="top">
            <div>
               <div class="title">DRE Gerencial</div>
               <div class="sub">Regime de competência — alimentado por Contas a Receber e Contas a Pagar</div>
            </div>
            <div>
               <label style="font-size:11px;color:#9aa5dc">MÊS DE COMPETÊNCIA</label><br>
               <select class="btn" style="height: 43px;">
                  <option>Jul/26</option>
               </select>
            </div>
         </div>
         <div class="notice"><i class="fa fa-info-circle" aria-hidden="true"></i>  Os valores abaixo são apurados pela data de vencimento (regime de competência). Receitas vêm das contas a receber e despesas das contas a pagar dentro do mês selecionado, respeitando as unidades do seu usuário.</div>
         <div class="cards">
            <div class="card">
               <div class="label">Receita Total</div>
               <div class="value branco">R$ 0,00</div>
               <div class="muted">0 lançamento(s)</div>
            </div>
            <div class="card card-border-red">
               <div class="label">Despesa Total</div>
               <div class="value red">R$ 0,00</div>
               <div class="muted">0 lançamento(s)</div>
            </div>
            <div class="card card-border-green">
               <div class="label">Resultado do Mês</div>
               <div class="value green">R$ 0,00</div>
               <div class="muted">Margem 0%</div>
            </div>
         </div>
         <div class="panel">
            <h3 class="branco">Estrutura — Jul/26</h3>
            <div class="row"><p>(+) RECEITAS</p></div>
            <div class="row muted"><p>Sem receitas no mês.</p></div>
            <div class="row"><strong>(=) Total de receitas</strong><strong>R$ 0,00</strong></div>
            <div class="row"><p>(-) DESPESAS</p></div>
            <div class="row muted"><p>Sem despesas no mês.</p></div>
            <div class="row"><strong>(=) Total de despesas</strong><strong class="red">(R$ 0,00)</strong></div>
            <div class="row"><strong>(=) RESULTADO DO PERÍODO</strong><strong>R$ 0,00</strong></div>
         </div>
         <div class="grid2">
            <div class="panel">
               <h3 class="branco">Comparativo - Jul/26 vs Jun/26</h3>
               <table>
                  <tr>
                     <th>Conta</th>
                     <th>Atual</th>
                     <th>Anterior</th>
                     <th>Δ</th>
                  </tr>
                  <tr>
                     <td>Receitas</td>
                     <td>R$0,00</td>
                     <td>R$31.000,00</td>
                     <td class="red">-100%</td>
                  </tr>
                  <tr>
                     <td>Despesas</td>
                     <td>R$0,00</td>
                     <td>R$0,00</td>
                     <td class="green">0%</td>
                  </tr>
                  <tr>
                     <td>Resultado</td>
                     <td>R$0,00</td>
                     <td>R$31.000,00</td>
                     <td class="red">-100%</td>
                  </tr>
               </table>
            </div>
            <div class="panel">
               <h3 class="branco">Evolução - últimos 12 meses</h3>
               <table>
                  <tr>
                     <th>Mês</th>
                     <th>Receitas</th>
                     <th>Despesas</th>
                     <th>Resultado</th>
                  </tr>
                  <tr>
                     <td>Ago/25</td>
                     <td>R$0,00</td>
                     <td class="red">(R$0,00)</td>
                     <td class="green">R$0,00</td>
                  </tr>
                  <tr>
                     <td>Jun/26</td>
                     <td>R$31.000,00</td>
                     <td class="red">(R$0,00)</td>
                     <td class="green">R$31.000,00</td>
                  </tr>
                  <tr>
                     <td>Jul/26</td>
                     <td>R$0,00</td>
                     <td class="red">(R$0,00)</td>
                     <td class="green">R$0,00</td>
                  </tr>
               </table>
            </div>
         </div>
      </div>

$file = 'C:\xampp\htdocs\smartcash\application\views\permissoes\editarPermissao.php'
$content = [System.IO.File]::ReadAllText($file, [System.Text.Encoding]::UTF8)

$groups = @(
    @{id='GFive'; title='Vendas'; icon='bx bx-cart-alt'},
    @{id='GSix'; title='Cobranças'; icon='bx bx-credit-card-front'},
    @{id='GSeven'; title='Garantias'; icon='bx bx-receipt'},
    @{id='GEight'; title='Arquivos'; icon='bx bx-box'},
    @{id='GNine'; title='Financeiro'; icon='bx bx-bar-chart-square'},
    @{id='UnidadeSolicitante'; title='Unidade Solicitante'; icon='bx bx-package'},
    @{id='UnidadeExecutante'; title='Unidade Executante'; icon='bx bx-package'},
    @{id='Procedimento'; title='Procedimento'; icon='bx bx-package'},
    @{id='LocalProcedimento'; title='Local do Procedimento'; icon='bx bx-package'},
    @{id='ItemAgendamento'; title='Item do Agendamento'; icon='bx bx-package'},
    @{id='AgendSolicitante'; title='Agendamento Solicitante'; icon='bx bx-package'},
    @{id='AgendExecutante'; title='Agendamento Executante'; icon='bx bx-package'},
    @{id='TodosAgendamentos'; title='Todos os Agendamentos'; icon='bx bx-package'},
    @{id='CentralMarcacao'; title='Central de marcação'; icon='bx bx-package'},
    @{id='GTen'; title='Relatórios'; icon='bx bx-chart'},
    @{id='GEleven'; title='Configurações e Sistema'; icon='bx bx-cog'}
)

$count = 0
foreach ($g in $groups) {
    $title = $g.title
    $icon = $g.icon
    $collapseId = "collapse$($g.id)"
    
    # Pattern for opening
    $oldOpen = "                <div class=""accordion-group widget-box"">
                    <div class=""accordion-heading"">
                        <div class=""widget-title"">
                            <a data-parent=""#collapse-group"" href=""#$collapseId"" data-toggle=""collapse"">
                                <span><i class='$icon' ></i></span>
                                <h5 style=""padding-left: 28px"">$title</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class=""collapse accordion-body"" id=""$collapseId"">"
    
    $newOpen = "                <details class=""widget-box"">
                    <summary>
                                <span><i class='$icon' ></i></span>
                                $title
                    </summary>
                        <div class=""widget-content"">"
    
    if ($content.Contains($oldOpen)) {
        $content = $content.Replace($oldOpen, $newOpen)
        $count++
        Write-Host "Converted: $title"
    } else {
        # Try with "in" class
        $oldOpen2 = "                <div class=""accordion-group widget-box"">
                    <div class=""accordion-heading"">
                        <div class=""widget-title"">
                            <a data-parent=""#collapse-group"" href=""#$collapseId"" data-toggle=""collapse"">
                                <span><i class='$icon' ></i></span>
                                <h5 style=""padding-left: 28px"">$title</h5>
                                <span><i class='bx bx-chevron-right icon-clic'></i></span>
                            </a>
                        </div>
                    </div>
                    <div class=""collapse in accordion-body"" id=""$collapseId"">"
        if ($content.Contains($oldOpen2)) {
            $content = $content.Replace($oldOpen2, $newOpen)
            $count++
            Write-Host "Converted (with .in): $title"
        } else {
            Write-Host "NOT FOUND: $title"
        }
    }
}

# Now replace all closing patterns - </div></div></div> that close accordion-group
# These are the 3 closing divs after widget-content
# Pattern: </div> (close widget-content) then </div> (close collapse-body) then </div> (close accordion-group)
# Find each separately with context, or do a more targeted approach

# Replace the generic closing pattern for accordion groups
# After each table content, we have: </div> (close widget-content), </div> (close collapse), </div> (close accordion-group)
# We'll replace the last two </div> with nothing and change the first </div> to </details>

$closingOld = "                        </div>
                    </div>
                </div>"
$closingNew = "                        </div>
                </details>"

if ($content.Contains($closingOld)) {
    $content = $content.Replace($closingOld, $closingNew)
    Write-Host "Replaced closing patterns"
}

[System.IO.File]::WriteAllText($file, $content, [System.Text.Encoding]::UTF8)
Write-Host "Total conversions: $count"
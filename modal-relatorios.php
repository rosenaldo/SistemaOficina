<!-- Modal Relatórios - Estilo Unificado -->
<style>
    /* Estilos para todos os modais de relatório */
    .modal-report {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    }
    
    .modal-report .modal-header {
        background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-bottom: none;
    }
    
    .modal-report .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .modal-report .modal-body {
        padding: 1.5rem;
    }
    
    .modal-report .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }
    
    .modal-report .form-group label {
        font-weight: 500;
        color: #495057;
    }
    
    .modal-report .form-control {
        border-radius: 0.35rem;
        height: calc(2.25rem + 6px);
    }
    
    .modal-report .btn-primary {
        background-color: #7C3AED;
        border-color: #7C3AED;
    }
    
    .modal-report .btn-primary:hover {
        background-color: #5B21B6;
        border-color: #5B21B6;
    }
    
    .modal-report .close {
        color: white;
        opacity: 1;
        text-shadow: none;
    }
</style>

<!-- Modal Relatório de Serviços -->
<div class="modal fade" id="ModalRelServicos" tabindex="-1" role="dialog" aria-labelledby="ModalRelServicosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelServicosLabel">
                    <i class="fas fa-tools mr-2"></i>Relatório de Serviços
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_servicos.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option>
                                    <option value="Não">Não Concluídos</option>
                                    <option value="Sim">Concluídos</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Orçamentos -->
<div class="modal fade" id="ModalRelOrc" tabindex="-1" role="dialog" aria-labelledby="ModalRelOrcLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelOrcLabel">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>Relatório de Orçamentos
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_orcamentos.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option>
                                    <option value="Aberto">Aberto</option>
                                    <option value="Aprovado">Aprovado</option>
                                    <option value="Concluído">Concluído</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Movimentações -->
<div class="modal fade" id="ModalRelMov" tabindex="-1" role="dialog" aria-labelledby="ModalRelMovLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelMovLabel">
                    <i class="fas fa-exchange-alt mr-2"></i>Relatório de Movimentações
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_mov.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tipo</label>
                                <select class="form-control" name="status">
                                    <option value="">Todos</option>
                                    <option value="Entrada">Entrada</option>
                                    <option value="Saída">Saída</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Contas a Pagar -->
<div class="modal fade" id="ModalRelPagar" tabindex="-1" role="dialog" aria-labelledby="ModalRelPagarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelPagarLabel">
                    <i class="fas fa-money-bill-wave mr-2"></i>Contas à Pagar
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_pagar.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option>
                                    <option value="Sim">Pagas</option>
                                    <option value="Não">Pendentes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Contas a Receber -->
<div class="modal fade" id="ModalRelReceber" tabindex="-1" role="dialog" aria-labelledby="ModalRelReceberLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelReceberLabel">
                    <i class="fas fa-hand-holding-usd mr-2"></i>Contas à Receber
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_receber.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">Todas</option>
                                    <option value="Sim">Recebidas</option>
                                    <option value="Não">Pendentes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Compras -->
<div class="modal fade" id="ModalRelCompras" tabindex="-1" role="dialog" aria-labelledby="ModalRelComprasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelComprasLabel">
                    <i class="fas fa-shopping-cart mr-2"></i>Relatório de Compras
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_compras.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Relatório de Vendas -->
<div class="modal fade" id="ModalRelVendas" tabindex="-1" role="dialog" aria-labelledby="ModalRelVendasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-report">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalRelVendasLabel">
                    <i class="fas fa-cash-register mr-2"></i>Relatório de Vendas
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../rel/rel_vendas.php" method="POST" target="_blank">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Inicial</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataInicial" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Data Final</label>
                                <input value="<?php echo date('Y-m-d') ?>" type="date" class="form-control" name="dataFinal" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-pdf mr-1"></i> Gerar Relatório
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
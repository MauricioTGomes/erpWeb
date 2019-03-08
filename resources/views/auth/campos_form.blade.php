
        <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="Nome completo" name="name"password2 value="{{ isset($user) ? $user->name : null }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        @if(auth()->user()->tipo == 'gerente')
        <div class="form-group has-feedback">
            <input type="text" class="form-control input-positive" placeholder="Porcentagem de comissão" name="porcentagem_comissao" value="{{ isset($user) ? $user->porcentagem_comissao : null }}">
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        @endif
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ isset($user) ? $user->email : null }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        @if(isset($user))
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Senha antiga" name="password_antigo">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Digite nova senha" name="password">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Repita a nova senha" name="password2">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        @else
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Senha" name="password" >
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Repita a senha" name="password2">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
        </div>
        @endif

        @if(auth()->user()->tipo == 'gerente')
        <label class="form-group has-feedback col-xs-12">
            <span class="block input-icon input-icon-right">
            <select class="form-control select2" name="tipo">
            <option {{ isset($user) && $user->tipo == 'vendedor' ? 'selected' : ''}} value="vendedor">Vendedor</option>
            <option {{ isset($user) && $user->tipo == 'gerente' ? 'selected' : ''}} value="gerente">Gerente</option>
            </select>
            </span>
        </label>
        @endif

        <div class="row">
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Salvar</button>
            </div>
        </div>
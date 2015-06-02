<div id="call-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header">
                <h1>Plugin Demo: Video MCU
                    <button class="btn btn-default" autocomplete="off" id="start">Start</button>
                </h1>
            </div>
            <div class="container" id="details">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Room details</h3>
                        <p>This is a multi-user conference.</p>
                        <p>Press the <code>Start</code> button above to launch the conference.</p>
                    </div>
                </div>
            </div>
            <div class="container hide" id="videojoin">
                <div class="row">
                    <span class="label label-info" id="you"></span>
                    <div class="col-md-12" id="controls">
                        <div class="input-group margin-bottom-md hide" id="registernow">
                            <span class="input-group-addon">@</span>
                            <input autocomplete="off" class="form-control" autocomplete="off" type="text" placeholder="Choose a display name" id="username" onkeypress="return checkEnter(this, event);"></input>
							<span class="input-group-btn">
								<button class="btn btn-success" autocomplete="off" id="register">Join the room</button>
							</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container hide" id="videos">
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Local Video <span class="label label-primary hide" id="publisher"></span></h3>
                            </div>
                            <div class="panel-body" id="videolocal"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remote Video #1 <span class="label label-info hide" id="remote1"></span></h3>
                            </div>
                            <div class="panel-body relative" id="videoremote1"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remote Video #2 <span class="label label-info hide" id="remote2"></span></h3>
                            </div>
                            <div class="panel-body relative" id="videoremote2"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remote Video #3 <span class="label label-info hide" id="remote3"></span></h3>
                            </div>
                            <div class="panel-body relative" id="videoremote3"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remote Video #4 <span class="label label-info hide" id="remote4"></span></h3>
                            </div>
                            <div class="panel-body relative" id="videoremote4"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Remote Video #5 <span class="label label-info hide" id="remote5"></span></h3>
                            </div>
                            <div class="panel-body relative" id="videoremote5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
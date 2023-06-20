<?php

namespace App\Console\Commands;

use Ratchet\Server\IoServer;

use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Http\Controllers\WebSocketController;
use Illuminate\Console\Command;

class WebSocketServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket.init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $server = IoServer::factory(
            new HttpServer(new WsServer(new WebSocketController())),
            8282
        );

        $server->run();
    }
}

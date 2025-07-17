    package com.coditech.gestion.controller;

    import com.coditech.gestion.service.ClienteService;
    import com.coditech.gestion.model.Cliente;
    import org.springframework.beans.factory.annotation.Autowired;
    import org.springframework.web.bind.annotation.*;

    import java.util.List;

    @RestController
    @RequestMapping("/api/clientes")
    public class ClienteController {

        @Autowired
        private ClienteService clienteService;

        @GetMapping
        public List<Cliente> obtenerTodos() {
            return clienteService.listarTodos();
        }

        @PostMapping
        public Cliente crear(@RequestBody Cliente cliente) {
            return clienteService.guardar(cliente);
        }

        @GetMapping("/{id}")
        public Cliente obtenerPorId(@PathVariable Long id) {
            return clienteService.buscarPorId(id);
        }

        @DeleteMapping("/{id}")
        public void eliminar(@PathVariable Long id) {
            clienteService.eliminar(id);
        }
        @PutMapping("/{id}")
        public Cliente actualizar(@PathVariable Long id, @RequestBody Cliente cliente) {
            Cliente clienteExistente = clienteService.buscarPorId(id);
            if (clienteExistente != null) {
                clienteExistente.setNombre(cliente.getNombre());
                clienteExistente.setApellido(cliente.getApellido());
                clienteExistente.setCorreo(cliente.getCorreo());
                clienteExistente.setTelefono(cliente.getTelefono());
                return clienteService.guardar(clienteExistente);
            } else {
                return null;
            }
        }
    }

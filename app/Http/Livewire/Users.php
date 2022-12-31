<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use Livewire\Component;
use Spatie\Permission\Models\Role;  //trait para los permisos
use Livewire\WithFileUploads;   //trait para subir imagenes
use Livewire\WithPagination;    //trait para la paginacion
use App\Models\User;

class Users extends Component
{
    use WithPagination; //llamado a los trait
    use WithFileUploads;    //llamado a los trait

    //propiedades publicas dentro del backend para accesar desde el frontend
    public $name,$phone,$email,$status,$profile,$image,$password,$selected_id,$fileLoaded,$pageTitle,$componentName,$search;
    private $pagination = 20;    //paginacion

    public function paginationView(){   //metodo para la paginacion personalizada
        return 'vendor.livewire.bootstrap'; //archivo de la paginacion
    }

    public function mount(){    //metodo que se ejecuta en cuanto se monta este componente
        $this->pageTitle = 'Listado';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->componentName = 'Usuarios';  //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->status = 'Elegir';   //inicializar propiedades o informacion que se va renderizar en la vista principal del componente
        $this->search = null;
    }

    public function render()
    {
        if(strlen($this->search) > 0)   //validar si la caja de busqueda tiene algo escrito con metodo php strlen
            $data = User::where('name', 'like', '%' . $this->search . '%')  //busqueda por nombre y guardado en variable
            ->select('*')->orderBy('name', 'asc')->paginate($this->pagination);
        else    //caso contrario devuelve el listado ordenado por nombre
            $data = User::select('*')->orderBy('name', 'asc')->paginate($this->pagination);

        //retorna la vista con la informacion almacenada en variable
        //listado de usuarios y roles
        return view('livewire.user.users', [
            'users' => $data,
            'roles' => Role::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')  //indicamos que la vista que estamos retornando extiende de esta plantilla
        ->section('content');   //contenido del componente que se renderiza en esta seccion
    }

    public function resetUI(){  //metodo para limpiar la informacion de las propiedades publicas

        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->search = null;
        $this->search = null;
        $this->status = 'Elegir';
        $this->selected_id = 0;
        $this->resetValidation();   //metodo para limpiar las validaciones del formulario
        $this->resetPage(); //metodo de livewire para volver al listado principal
    }

    protected $listeners = [    //eventos provenientes del frontend a ser escuchados
        'destroy' => 'Destroy', //al escuchar el evento destroy se hace llamado al metodo Destroy
        'resetUI' => 'resetUI'  //al escuchar el evento resetUI se hace llamado al metodo resetUI
    ];

    public function Store(){    //metodo para almacenar nuevos registros

        $rules = [  //reglas de validacion para cada campo en especifico
            'name' => 'required|min:3',
            'email' => 'required|unique:users|email',
            'status' => 'required|not_in:Elegir',
            'profile' => 'required|not_in:Elegir',
            'password' => 'required|min:8'
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre de usuario debe contener al menos 3 caracteres',
            'email.required' => 'El correo es requerido',
            'email.unique' => 'El email ya ha sido registrado por otro usuario',
            'email.email' => 'Ingrese un correo valido',
            'status.required' => 'El estado es requerido',
            'status.not_in' => 'Elija un estado',
            'profile.required' => 'El perfil/rol es requerido',
            'profile.not_in' => 'Elija un perfil/rol',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres'
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $user = User::create([  //guardar en variable el registro de este elemento
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'profile' => $this->profile,
            'password' => bcrypt($this->password)   //bcrypt para encriptar
        ]);

        $user->syncRoles($this->profile);   //asociar un rol con el perfil del usuario

        if($user){

            if($this->image){
            
                $customFileName = uniqid() . '_.' . $this->image->extension();
                $this->image->storeAs('public/users', $customFileName);
                $user->image()->create(['url' => $customFileName]);
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-added', 'Usuario registrado');    //evento a ser escuchado desde el frontend
    }

    public function edit(User $user){   //metodo para abrir modal edit pasandole el id del elemento seleccionado

        //llenado de los campos con los datos almacenados en variable
        $this->selected_id = $user->id;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->profile = $user->profile;
        $this->status = $user->status;
        $this->email = $user->email;
        $this->password = '';
        $this->image = null;
        $this->emit('show-modal', 'Mostrar Modal'); //evento a ser escuchado desde el frontend
    }

    public function Update(){   //metodo para actualizar registro

        $rules = [  //reglas de validacion para cada campo en especifico
            //aqui se valida que no exista otro registro con el mismo nombre excluyendo el id seleccionado actualmente
            'email' => "required|email|unique:users,email,{$this->selected_id}",
            'name' => 'required|min:3',
            'status' => 'required|not_in:Elegir',
            'profile' => 'required|not_in:Elegir',
            'password' => 'required|min:8'
        ];

        $messages = [   //mensajes para cada error de validacion para ser recibidos desde el frontend
            'name.required' => 'El nombre es requerido',
            'name.min' => 'El nombre de usuario debe contener al menos 3 caracteres',
            'email.required' => 'El correo es requerido',
            'email.email' => 'Ingrese un correo valido',
            'email.unique' => 'El email ya esta registrado en el sistema',
            'status.required' => 'El estado es requerido',
            'status.not_in' => 'Elija un estado',
            'profile.required' => 'El perfil/rol es requerido',
            'profile.not_in' => 'Elija un perfil/rol',
            'password.required' => 'La contraseña es requerida',
            'password.min' => 'La contraseña debe contener al menos 8 caracteres'
        ];

        $this->validate($rules, $messages); //ejecutar las validaciones con metodo validate que nos solicita 2 parametros (reglas,mensajes)

        $user = User::find($this->selected_id); //seleccionar el registro del id seleccionado actualmente y guardar en variable
        
        if($this->password != null){

        $user->update([ //actualizar el registro con metodo update de eloquent
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,
            'profile' => $this->profile,
            'password' => bcrypt($this->password)   //bcrypt para encriptar
        ]);

        }else{

            $user->update([ //actualizar el registro con metodo update de eloquent
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
                'profile' => $this->profile,
                'password' => bcrypt($user->password)   //bcrypt para encriptar
            ]);
        }

        $user->syncRoles($this->profile);   //asociar un rol con el perfil del usuario
        //$user->syncPermissions($this->profile);

        if($this->image){   //validar si se selecciono una imagen para el registro
            //metodo de php uniqid() para asignar id automatico y unico
            $customFileName = uniqid() . '_.' . $this->image->extension();  //guardar en variable el id concatenado de _.extension del archivo seleccionado
            //metodo storeAs solicita 2 parametros (directorio para almacenar archivo, nombre del archivo)
            $this->image->storeAs('public/users', $customFileName); //almacenar informacion
            
            if($user->image != null){

                $imageTemp = $user->image->url;      //recuperar y guardar en una variable temporal el archivo almacenado originalmente
                $user->image()->update(['url' => $customFileName]);     //actualizar la columna image con el archivo seleccionado actualmente

                if($imageTemp != null){     //validar si variable temporal tiene algo almacenado dentro
                    //metodo file_exists de php requiere un parametro (directorio + nombre de archivo) para verificar si un archivo existe fisicamente
                    if(file_exists('storage/users/' . $imageTemp)){     //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                        //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                        unlink('storage/users/' . $imageTemp);      //si existe fisicamente se elimina ese archivo del directorio
                    }
                }

            }else{

                $user->image()->create(['url' => $customFileName]);
            }
        }

        $this->resetUI();   //limpiar la informacion de los campos del formulario
        $this->emit('item-updated', 'Usuario actualizado'); //evento a ser escuchado desde el frontend
    }

    public function Destroy(User $user){    //metodo para eliminar registros con una instancia del modelo que contiene el id

        if($user){  //validar si el usuario existe

            $sales = Sale::where('user_id', $user->id)->count();    //obtener conteo de ventas de usuario y guardar en variable

            if($sales > 0){ //validar si usuario tiene ventas

                $this->emit('user-with-sales', 'No es posible eliminar al usuario mientras tenga ventas registradas');  //evento a ser escuchado desde el frontend

            }else{

                $user->delete();    //eliminar registro con metodo delete de eloquent

                if($user->image != null){

                    $imageTemp = $user->image->url;     //recuperar y guardar en una variable temporal el archivo almacenado originalmente
                    $user->image()->delete();
        
                    if($imageTemp != null){ //validar si el registro tenia una imagen guardada
        
                        if(file_exists('storage/users/' . $imageTemp)){     //validar si lo que tiene almacenado la variable temporal existe fisicamente en el directorio
                            //metodo unlink de php requiere un parametro (directorio + nombre de archivo) para eliminar un archivo fisicamente
                            unlink('storage/users/' . $imageTemp);      //si existe fisicamente se elimina ese archivo del directorio
                        }
                    }
                }

                $this->resetUI();   //limpiar la informacion de los campos del formulario
                $this->emit('item-deleted', 'Usuario eliminado');   //evento a ser escuchado desde el frontend
            }
        }
    }
}

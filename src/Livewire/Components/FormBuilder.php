<?php
namespace Elearnac\PageBuilder\Livewire\Components;
use Elearnac\PageBuilder\Models\FormSubmission;
use Livewire\Attributes\On;
class FormBuilder extends BlockComponent {
    public array $values = [];
    public bool $sent = false;
    public function fields(): array {
        $out=[];
        foreach(explode("\n",(string)$this->setting('fields','')) as $line){
            $parts=array_map('trim',explode('|',$line));
            if(count($parts)>=2 && $parts[0]!=='') $out[]=['name'=>$parts[0],'label'=>$parts[1],'type'=>$parts[2]??'text'];
        }
        return $out;
    }
    public function submit(): void {
        $rules=[]; foreach($this->fields() as $f){ $rules["values.{$f['name']}"]=$f['type']==='email'?'required|email':'required|string|max:2000'; }
        $this->validate($rules);
        FormSubmission::create(['form_id'=>(string)$this->setting('form_id','form'),'payload'=>$this->values]);
        $this->sent=true; $this->values=[];
    }
    public function render(){ return view('page-builder::components.form',['fields'=>$this->fields()]); }
}

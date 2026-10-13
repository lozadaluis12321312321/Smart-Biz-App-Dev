// An accessible, in-page confirmation keeps each transaction in context.
const confirmation=document.createElement('dialog');
confirmation.className='confirm-dialog';
confirmation.setAttribute('aria-labelledby','confirmation-title');
confirmation.setAttribute('aria-describedby','confirmation-copy');
confirmation.innerHTML='<span class="mini-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="m12 2 9 4v6c0 5-9 10-9 10S3 17 3 12V6l9-4Z"/><path d="m8 12 3 3 5-6"/></svg></span><h2 id="confirmation-title">Confirm this action</h2><p id="confirmation-copy"></p><div class="confirm-actions"><button type="button" class="btn btn-outline-dark" data-dismiss>Go back</button><button type="button" class="btn btn-primary" data-approve>Confirm</button></div>';
document.body.append(confirmation);
let pendingForm=null,pendingSubmitter=null,approvedForm=null;
document.querySelectorAll('form[data-confirm], form[data-confirm-actions]').forEach(form=>form.addEventListener('submit',event=>{
 if(approvedForm===form){approvedForm=null;return;}
 const message=event.submitter?.dataset.confirm || form.dataset.confirm;
 if(!message)return;
 event.preventDefault();pendingForm=form;pendingSubmitter=event.submitter;
 confirmation.querySelector('#confirmation-copy').textContent=message;
 confirmation.showModal();confirmation.querySelector('[data-dismiss]').focus();
}));
confirmation.querySelector('[data-dismiss]').addEventListener('click',()=>{confirmation.close();pendingForm=null;});
confirmation.addEventListener('cancel',()=>{pendingForm=null;});
confirmation.querySelector('[data-approve]').addEventListener('click',()=>{
 const form=pendingForm,submitter=pendingSubmitter;confirmation.close();pendingForm=null;
 if(form){approvedForm=form;form.requestSubmit(submitter || undefined);}
});
// Associate every visible form label with its input, including generic master forms.
document.querySelectorAll('label:not([for])').forEach((label,index)=>{
 let input=label.querySelector('input,select,textarea');
 let sibling=label.nextElementSibling;
 while(!input && sibling && !sibling.matches('label')){input=sibling.matches('input:not([type=hidden]),select,textarea')?sibling:sibling.querySelector('input:not([type=hidden]),select,textarea');sibling=sibling.nextElementSibling;}
 if(input){if(!input.id)input.id=`field-${index}`;label.htmlFor=input.id;}
});
const sidebar=document.querySelector('#sidebar'),toggle=document.querySelector('.menu-toggle');
if(sidebar&&toggle){
 const scrim=document.createElement('button');scrim.className='mobile-scrim';scrim.setAttribute('aria-label','Close navigation');document.body.append(scrim);
 const close=()=>{sidebar.classList.remove('open');scrim.classList.remove('visible');toggle.setAttribute('aria-expanded','false');};
 toggle.setAttribute('aria-controls','sidebar');toggle.setAttribute('aria-expanded','false');
 toggle.addEventListener('click',()=>{const open=sidebar.classList.contains('open');scrim.classList.toggle('visible',open);toggle.setAttribute('aria-expanded',String(open));});
 scrim.addEventListener('click',close);document.addEventListener('keydown',event=>{if(event.key==='Escape')close();});
}
const peso=cents=>new Intl.NumberFormat('en-PH',{style:'currency',currency:'PHP'}).format(cents/100);
document.querySelectorAll('[data-line-editor]').forEach(editor=>{
 const body=editor.querySelector('[data-lines]');let index=body.children.length;
 const update=()=>{let sum=0;body.querySelectorAll('[data-line]').forEach(row=>{const product=row.querySelector('[data-product]'),cost=row.querySelector('[data-cost]');const cents=cost?Math.round((Number(cost.value)||0)*100):Number(product.selectedOptions[0]?.dataset.price||0);const total=cents*(Number(row.querySelector('[data-qty]').value)||0);sum+=total;row.querySelector('[data-line-total]').textContent=peso(total);});editor.querySelector('[data-items-total]').textContent=peso(sum);};
 editor.addEventListener('input',update);editor.addEventListener('change',e=>{if(e.target.matches('[data-product]')){const cost=e.target.closest('tr').querySelector('[data-cost]');if(cost)cost.value=(Number(e.target.selectedOptions[0]?.dataset.price||0)/100).toFixed(2);}update();});
 editor.addEventListener('click',e=>{if(e.target.matches('[data-remove]')&&body.children.length>1){e.target.closest('tr').remove();update();}});
 editor.querySelector('[data-add-line]').addEventListener('click',()=>{if(body.children.length>=50)return;const row=body.firstElementChild.cloneNode(true);row.querySelectorAll('[name]').forEach(input=>{input.name=input.name.replace(/items\[\d+\]/,`items[${index}]`);input.value=input.matches('[data-qty]')?'1':'';});index++;body.append(row);update();});update();
 // Purchase orders only list the products the chosen supplier provides.
 const supplier=editor.closest('form')?.querySelector('[data-supplier-select]');
 if(supplier){
  const filter=()=>{const id=supplier.value;body.querySelectorAll('[data-product]').forEach(select=>{select.querySelectorAll('option[data-supplier]').forEach(opt=>{const match=!id||opt.dataset.supplier===id;opt.hidden=!match;opt.disabled=!match;if(!match&&opt.selected)select.value='';});});const count=body.querySelector('[data-product]')?.querySelectorAll('option[data-supplier]:not([disabled])').length||0;const note=editor.querySelector('[data-supplier-note]');if(note)note.textContent=!id?'Select a supplier above to see their products.':(count?`${count} product${count===1?'':'s'} available from this supplier.`:'This supplier has no products yet. Assign products to the supplier in Products first.');update();};
  supplier.addEventListener('change',filter);new MutationObserver(filter).observe(body,{childList:true});filter();
 }
});
const fulfill=document.querySelector('[data-fulfillment]');if(fulfill){const update=()=>{const delivery=fulfill.value==='Delivery';document.querySelectorAll('[data-delivery]').forEach(el=>el.hidden=!delivery);const address=document.querySelector('[name=delivery_address]');if(address)address.required=delivery;const total=document.querySelector('#checkout-total');if(total){const fee=delivery?Number(total.dataset.fee):0;document.querySelector('#delivery-display').textContent=peso(fee);total.textContent=peso(Number(total.dataset.subtotal)+fee);}};fulfill.addEventListener('change',update);update();}
const bill=document.querySelector('#bill-total');if(bill){const update=()=>{const cents=name=>Math.round((Number(document.querySelector(`[name=${name}]`).value)||0)*100);bill.textContent=peso(Number(bill.dataset.subtotal)-cents('discount')+cents('tax')+cents('additional')+cents('shipping'));};document.querySelectorAll('[data-bill-input]').forEach(input=>input.addEventListener('input',update));update();}
// Supplier page ordering: running total of the quantities entered.
document.querySelectorAll('[data-supplier-order]').forEach(form=>{
 const total=form.querySelector('[data-order-total]');
 const update=()=>{let sum=0;form.querySelectorAll('[data-order-qty]').forEach(input=>{sum+=(Number(input.value)||0)*Number(input.closest('tr').querySelector('[data-cost]').dataset.cost||0);});total.textContent=peso(sum);};
 form.addEventListener('input',update);update();
});
// Add/edit product: show the chosen supplier's inventory and let a product be used as a template.
(()=>{
 const choice=document.querySelector('[data-supplier-choice]'),panel=document.querySelector('[data-supplier-inventory]'),json=document.querySelector('[data-inventory-json]');
 if(!choice||!panel||!json)return;
 const inventory=JSON.parse(json.textContent||'{}');
 choice.closest('.col-md-6,.col-12').after(panel);
 const rows=panel.querySelector('[data-inventory-rows]'),empty=panel.querySelector('[data-inventory-empty]');
 const render=()=>{
  const id=choice.value,items=inventory[id]||[];
  panel.hidden=!id;if(!id)return;
  panel.querySelector('[data-inventory-title]').textContent=choice.selectedOptions[0].textContent.split(' · ')[0].replace(/ \(inactive\)$/,'');
  panel.querySelector('[data-inventory-count]').textContent=items.length;
  empty.hidden=items.length>0;rows.parentElement.parentElement.hidden=!items.length;
  rows.innerHTML=items.map(p=>`<tr><td><div class="table-product"><img src="${p.image}" alt=""><div><strong>${p.name}</strong><small class="d-block">${p.code}</small></div></div></td><td><small>${p.detail||'—'}</small></td><td>${p.available}</td><td><button type="button" class="btn btn-sm btn-outline-dark" data-template="${p.id}">Use as template</button></td></tr>`).join('');
 };
 rows.addEventListener('click',e=>{
  const button=e.target.closest('[data-template]');if(!button)return;
  const product=(inventory[choice.value]||[]).find(p=>String(p.id)===button.dataset.template);if(!product)return;
  Object.entries(product.fields).forEach(([name,value])=>{const field=document.querySelector(`#${name}`);if(field&&value!==null&&value!==undefined)field.value=value;});
  const name=document.querySelector('#name');button.textContent='Copied ✓';setTimeout(()=>button.textContent='Use as template',1500);
  if(name&&!name.value){name.focus();name.placeholder=`e.g. ${product.name} (new variant)`;}
 });
 choice.addEventListener('change',render);render();
})();
// Supplier product picker: filter the list, select all shown, enable the button once something is ticked.
document.querySelectorAll('[data-supplier-picker]').forEach(form=>{
 const rows=[...form.querySelectorAll('[data-picker-row]')],filter=form.querySelector('[data-picker-filter]'),all=form.querySelector('[data-picker-all]'),count=form.querySelector('[data-picker-count]'),submit=form.querySelector('[data-picker-submit]');
 const update=()=>{const n=form.querySelectorAll('[data-picker-box]:checked').length;count.textContent=n;submit.disabled=!n;};
 filter.addEventListener('input',()=>{const term=filter.value.trim().toLowerCase();rows.forEach(row=>row.hidden=term&&!row.dataset.text.includes(term));all.checked=false;});
 all.addEventListener('change',()=>{rows.filter(row=>!row.hidden).forEach(row=>row.querySelector('[data-picker-box]').checked=all.checked);update();});
 form.addEventListener('change',update);update();
});
// Philippine contact numbers get the same instant browser feedback as the email field.
document.querySelectorAll('[data-phone-ph]').forEach(input=>{
 const valid=value=>/^(09\d{9}|\+639\d{9}|0[2-8]\d{7,8}|\+63[2-8]\d{7,8})$/.test(value.replace(/[\s\-().]/g,''));
 const check=()=>{const value=input.value.trim();input.setCustomValidity(!value||valid(value)?'':'Enter a valid Philippine contact number, e.g. 0917 123 4567 or +63 917 123 4567.');};
 input.addEventListener('input',()=>{input.value=input.value.replace(/[^\d+\s\-()]/g,'');check();if(!input.checkValidity()&&input.value.replace(/\D/g,'').length>=11)input.reportValidity();});
 input.addEventListener('blur',()=>{check();if(input.value)input.reportValidity();});
 check();
});
// Close the account menu without interrupting keyboard or pointer navigation.
const accountMenu=document.querySelector('.account-menu');
if(accountMenu){document.addEventListener('click',event=>{if(!accountMenu.contains(event.target))accountMenu.open=false;});document.addEventListener('keydown',event=>{if(event.key==='Escape' && accountMenu.open){accountMenu.open=false;accountMenu.querySelector('summary').focus();}});}

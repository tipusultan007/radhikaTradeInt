@extends('layouts.app')
@section('title','Journal Entries')
@section('content')
    <div class="card">
       <div class="card-body">
           <table class="table table-bordered">
               <thead class="thead-dark">
               <tr style="background-color: #28a745; color: white;">
                   <th class="text-center">Date</th>
                   <th class="text-center">Type</th>
                   <th class="text-center" style="width: 290px">Account</th>
                   <th class="text-center">Debit</th>
                   <th class="text-center">Credit</th>
                   <th class="text-center">Action</th>
               </tr>
               </thead>
               <tbody>
               @foreach($journalEntries as $entry)
                   @php
                       $isFirstLineItem = true;
                   @endphp
                   @foreach($entry->lineItems as $item)
                       <tr>
                           @if($isFirstLineItem)

                               <td rowspan="{{ $entry->lineItems->count() }}">{{ $entry->date->format('d-M-y') }}</td>
                               <td rowspan="{{ $entry->lineItems->count() }}">{{ ucfirst($entry->type) }}
                               @if($entry->type === 'sale')
                                       <br> <strong> Inv #{{ $entry->journalable->invoice_no??'' }}</strong>
                               @endif
                               </td>
                               @php
                                   $isFirstLineItem = false;
                               @endphp
                           @endif

                           <td class="{{ $item->credit > 0 ? 'text-end' : '' }}">
                           @if($item->account_id == 3 || $item->account_id == 5)
                                   <a href="{{ route('customers.show',$entry->customer_id) }}">{{ $entry->customer->name }}</a>
                               @else
                                   {{ $item->account->name }}
                           @endif
                           </td>
                           <td class="text-success text-end">{{ $item->debit > 0 ? number_format($item->debit, 2) : '' }}</td>
                           <td class="text-danger text-end">{{ $item->credit > 0 ? number_format($item->credit, 2) : '' }}</td>
                               @if($loop->first)
                                   <!-- Conditionally show anchor link based on journal entry type -->
                                   <td rowspan="{{ $entry->lineItems->count() }}" class="text-center">
                                       @if($entry->type === 'sale')
                                           <a href="{{ route('sales.show', ['sale' => $entry->journalable_id]) }}" class="btn btn-success">View</a>
                                       @elseif($entry->type === 'purchase')
                                           <a href="{{ route('purchases.show', ['purchase' => $entry->journalable_id]) }}" class="btn btn-warning">View</a>
                                       @elseif($entry->type === 'payment')
                                           <a href="{{ route('payments.show', ['payment' => $entry->journalable_id]) }}" class="btn btn-info">View</a>
                                       @endif
                                   </td>
                               @endif
                       </tr>
                   @endforeach
               @endforeach
               </tbody>
           </table>
           {{ $journalEntries->links() }}
       </div>
    </div>
@endsection

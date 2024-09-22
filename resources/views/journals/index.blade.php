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
                   <th class="text-center">Account</th>
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
                               <td rowspan="{{ $entry->lineItems->count() }}">{{ ucfirst($entry->type) }}</td>
                               @php
                                   $isFirstLineItem = false;
                               @endphp
                           @endif

                           <td class="{{ $item->credit > 0 ? 'text-end' : '' }}">{{ $item->account->name }}</td>
                           <td class="text-success text-end">{{ $item->debit > 0 ? number_format($item->debit, 2) : '' }}</td>
                           <td class="text-danger text-end">{{ $item->credit > 0 ? number_format($item->credit, 2) : '' }}</td>
                               @if($loop->first)
                                   <!-- Conditionally show anchor link based on journal entry type -->
                                   <td rowspan="{{ $entry->lineItems->count() }}" class="text-center">
                                       @if($entry->type === 'sale')
                                           <a href="{{ route('sales.show', ['sale' => $entry->journalable_id]) }}" class="btn btn-success">View Sale</a>
                                       @elseif($entry->type === 'purchase')
                                           <a href="{{ route('purchases.show', ['purchase' => $entry->journalable_id]) }}" class="btn btn-warning">View Purchase</a>
                                       @elseif($entry->type === 'payment')
                                           <a href="{{ route('payments.show', ['payment' => $entry->journalable_id]) }}" class="btn btn-info">View Payment</a>
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

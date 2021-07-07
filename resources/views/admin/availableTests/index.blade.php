@extends('layouts.admin')
@section('content')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("available-test-create") }}">
                Add New Test
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            List of Available Tests
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class=" table table-bordered table-striped table-hover datatable datatable-Event">
                    <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            Id
                        </th>
                        <th>
                            Category
                        </th>
                        <th>
                            Name
                        </th>
                        <th>
                            Test Fee
                        </th>
                        <th>
                            Urgent Fee
                        </th>
                        <th>
                        Stander Completion Time
                        </th>
                        <th>
                        Urgent Completion Time
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($availableTests as $key => $availableTest)
                        <tr data-entry-id="{{ $availableTest->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $availableTest->id ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->category->Cname ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->name ?? '' }}
                            </td>
                            <td>
                                {{ $availableTest->testFee ?? '' }}
                            </td>

                            <td>
                                {{ $availableTest->urgentFee ?? '' }}
                            </td>
                            <td>
                            @if($availableTest->stander_timehour <= 0)
                                {{ $availableTest->stander_timehour  }}-Second
                              @elseif($availableTest->stander_timehour <= 1)
                                {{ $availableTest->stander_timehour  }}-Second
                              @elseif ($availableTest->stander_timehour <= 59 )
                               {{ $availableTest->stander_timehour  }}-Seconds
                               @elseif ($availableTest->stander_timehour <= 60)
                                {{ $availableTest->stander_timehour/60  }}-Minute
                              @elseif ($availableTest->stander_timehour > 60 && $availableTest->stander_timehour <= 3540)
                                {{ $availableTest->stander_timehour/60  }}-Minutes
                              @elseif ($availableTest->stander_timehour <= 3600)
                                {{ $availableTest->stander_timehour/3600  }}-Hour
                              @elseif ($availableTest->stander_timehour >= 3600 && $availableTest->stander_timehour <= 86399)
                                {{ $availableTest->stander_timehour/3600  }}-Hours
                              @elseif ($availableTest->stander_timehour <= 86400)
                                {{ $availableTest->stander_timehour/86400  }}-Day
                              @elseif ($availableTest->stander_timehour >= 86400)
                                {{ $availableTest->stander_timehour/86400  }}-Days
                              @else
                                {{ $availableTest->stander_timehour/86400  }}-Day
                            @endif 
                            </td>

                            <td>
                            @if($availableTest->urgent_timehour <= 0)
                                {{ $availableTest->urgent_timehour  }}-Second
                              @elseif($availableTest->urgent_timehour <= 1)
                                {{ $availableTest->urgent_timehour  }}-Second
                              @elseif ($availableTest->urgent_timehour <= 59 )
                               {{ $availableTest->urgent_timehour  }}-Seconds
                               @elseif ($availableTest->urgent_timehour <= 60)
                                {{ $availableTest->urgent_timehour/60  }}-Minute
                              @elseif ($availableTest->urgent_timehour > 60 && $availableTest->urgent_timehour <= 3540)
                                {{ $availableTest->urgent_timehour/60  }}-Minutes
                              @elseif ($availableTest->urgent_timehour <= 3600)
                                {{ $availableTest->urgent_timehour/3600  }}-Hour
                              @elseif ($availableTest->urgent_timehour >= 3600 && $availableTest->urgent_timehour <= 86399)
                                {{ $availableTest->urgent_timehour/3600  }}-Hours
                              @elseif ($availableTest->urgent_timehour <= 86400)
                                {{ $availableTest->urgent_timehour/86400  }}-Day
                              @elseif ($availableTest->urgent_timehour >= 86400)
                                {{ $availableTest->urgent_timehour/86400  }}-Days
                              @else
                                {{ $availableTest->urgent_timehour/86400  }}-Day
                            @endif 
                            </td>
                            <td> 
                                <a class="btn btn-xs btn-primary" href="{{ route('availabel-tests-show', $availableTest->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <a class="btn btn-xs btn-info" href="{{ route('availabel-tests-edit', $availableTest->id) }}">
                                    {{ trans('global.edit') }}
                                </a>
                                <form method="POST" action="{{ route("avaiable-test-delete", [$availableTest->id]) }}" onsubmit="return confirm('{{ trans('Are You Sure to Deleted  ?') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                    @can('event_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.events.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')
                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            };
            dtButtons.push(deleteButton);
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                order: [[1, 'desc']],
                pageLength: 100,
            });
            $('.datatable-Event:not(.ajaxTable)').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })



    </script>
@endsection
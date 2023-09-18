<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Http\Request;
use SebastianBergmann\Diff\Diff;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use App\DataTables\TransactionDataTable;
use DateTime;

class TransactionController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $datas = Transaction::with(['member:id,name', 'details', 'details.book']);

        if (isset($request->date_start) and isset($request->status)) {
            if ($request->date_start != '0') {
                $datas->whereDate('date_start', $request->date_start);
            }
            if ($request->status != "0") {
                $datas->where('status', $request->status);
            }
            $datas->get();
            return $this->generateDataTable($datas);
        }

        $transactions = DB::table('transactions')->distinct()->get('date_start');
        $tr = Transaction::all();

        $tes = date("Y-m-d");

        $late_date = Transaction::with('member:id,name')
            ->where('date_end', '<', $tes)
            ->where('status', '=', 1)->get();

        $count = $late_date->count();

        return view('pages.transaction.index', compact('transactions', 'late_date', 'count'));
    }

    public function generateDataTable($datas)
    {

        $datatables = datatables()->of($datas)
            ->addColumn('date start', function ($transaction) {
                return convert_date($transaction->date_start);
            })
            ->addColumn('date end', function ($transaction) {
                return convert_date($transaction->date_end);
            })
            ->addColumn('days', function ($transaction) {
                $start =  Carbon::parse($transaction->date_start);
                $end =  Carbon::parse($transaction->date_end);
                $days = $start->diffInDays($end);
                $days = $days . ' hari';
                return $days;
            })
            ->addColumn('amount', function ($transaction) {
                return count($transaction->details);
            })

            ->addColumn('price', function ($transaction) {
                return count($transaction->details);
            })


            ->addColumn('status_tr', function ($transaction) {
                if ($transaction->status == 1) {
                    return "belum dikembalikan";
                } else {
                    return "sudah dikembalikan";
                }
            })

            ->addColumn('action', 'pages.transaction.action')
            ->rawColumns(['action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }
    public function api(Request $request)
    {
        if (auth()->user()->can('pengurus pinjaman')) {
            $transactions = Transaction::with(['member:id,name', 'details', 'details.book'])->get();

            $datatables = datatables()->of($transactions)
                ->addColumn('date start', function ($transaction) {
                    return convert_date($transaction->date_start);
                })
                ->addColumn('date end', function ($transaction) {
                    return convert_date($transaction->date_end);
                })
                ->addColumn('days', function ($transaction) {
                    $start =  Carbon::parse($transaction->date_start);
                    $end =  Carbon::parse($transaction->date_end);
                    $days = $start->diffInDays($end);
                    $days = $days . ' hari';
                    return $days;
                })
                ->addColumn('amount', function ($transaction) {
                    return count($transaction->details);
                })

                ->addColumn('price', function ($transaction) {
                    return count($transaction->details);
                })

                ->addColumn('status_tr', function ($transaction) {
                    if ($transaction->status == 1) {
                        return "belum dikembalikan";
                    } else {
                        return "sudah dikembalikan";
                    }
                })

                ->addColumn('action', 'pages.transaction.action')
                ->rawColumns(['action'])
                ->addIndexColumn();

            return $datatables->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $books = Book::all();
        return view('pages.transaction.create', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "member_id" => "required|numeric|unique:transactions,member_id",
            "date_start" => "required|date",
            "date_end" => "required|date",
        ]);

        $transactions = Transaction::create($data);

        $tr_details = [];
        // dd($request->book_id);

        foreach ($request->book_id as $value) {
            $tr_details[] = [
                'transaction_id' => $transactions->id,
                'book_id' => $value
            ];
        }
        DB::table('transaction_details')->insert($tr_details);

        $book = Book::with('details')->whereIn('id', $request->book_id)->get()->toArray();

        foreach ($book as $data) {
            DB::table('books')->where('id', $data['id'])->update(['qty' => $data['qty'] - 1]);
        }

        return redirect()->route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tes = date("Y-m-d");
        $transactions = Transaction::with(['member:id,name', 'details', 'details.book'])->find($id);
        $late_date = Transaction::with('member:id,name')
            ->where('date_end', '<', $tes)
            ->where('status', '=', 1)->get();
        $members = Member::all();
        $books = Book::all();

        return view('pages.transaction.show', compact('transactions', 'members', 'books', 'late_date'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)

    {
        $tes = date("Y-m-d");
        $transactions = Transaction::with(['member:id,name', 'details', 'details.book'])->find($id);
        $late_date = Transaction::with('member:id,name')
            ->where('date_end', '<', $tes)
            ->where('status', '=', 1)->get();
        $members = Member::all();
        $books = Book::all();

        return view('pages.transaction.edit', compact('transactions', 'members', 'books', 'late_date'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules =  [
            "member_id" => "required|numeric",
            "status" => "required|in:1,2",
            "date_start" => "required|date",
            "date_end" => "required|date",
            "status" => "required"
        ];

        $validateData = $request->validate($rules);
        $transaction = Transaction::findOrFail($id);

        $transaction->update($validateData);

        $tr_details = [];

        foreach ($request->book_id as $value) {
            $tr_details[]  = [
                'transaction_id' => $transaction->id,
                'book_id' => $value,
            ];
        }

        $post_book = $request->book_id;

        $existing_detail_id = TransactionDetail::where('transaction_id', $transaction->id)->select('id', 'book_id')->get();

        $details = $existing_detail_id->toArray();

        $details_book_id = [];
        $transaction_details_id = [];

        foreach ($details as $detail) {
            if (!in_array($detail['book_id'], $details_book_id)) {
                $details_book_id[] = $detail['book_id'];
                $transaction_details_id[] = $detail['id'];
            }
        }

        foreach ($details_book_id as $key => $book) {
            if (!in_array($book, $post_book)) {
                TransactionDetail::where('book_id', $book)->where('id', $transaction_details_id[$key])->delete();
            };
        }

        foreach ($post_book as $pbook) {
            if (!in_array($pbook, $details_book_id)) {
                DB::table('transaction_details')->insert([
                    'transaction_id' => $transaction->id,
                    'book_id' => $pbook,
                ]);
            }
        }

        $book = Book::with('details')->whereIn('id', $request->book_id)->get()->toArray();

        if ($request->status == "2") {
            foreach ($book as $data) {
                DB::table('books')->where('id', $data['id'])->update(['qty' => $data['qty'] + 1]);
            }
        }

        return redirect()->route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        $transaction->details()->delete();
        $transaction->delete();
        return redirect()->route('transactions.index');
    }
}

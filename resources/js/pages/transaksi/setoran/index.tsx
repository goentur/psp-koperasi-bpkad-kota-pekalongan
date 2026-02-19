import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, IndexGate } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useState } from 'react'
import Filters from './components/filters'
import { PlusCircle } from 'lucide-react'
import FormTabunganBaru from './components/form-tabungan-baru'
import FormPinjamanBaru from './components/form-pinjaman-baru'
import FormSetoranBaru from './components/form-setoran-baru'
import FormAngsuran from './components/form-angsuran'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: 'dashboard',
    },
    {
        title: 'Transaksi',
        href: 'transaksi.setoran.index',
    },
    {
        title: 'Tabungan',
        href: 'transaksi.setoran.index',
    },
];

export default function Index({ gate }: IndexGate) {
    const title = 'Setoran'
    const [dataTabungan, setDataTabungan] = useState<[]>([])
    const [loadingDataTabungan, setLoadingDataTabungan] = useState(false)
    const [formTabungan, setFormTabungan] = useState(false)
    const [formSetoran, setFormSetoran] = useState(false)
    const [loadingDataPinjaman, setLoadingDataPinjaman] = useState(false)
    const [dataPinjaman, setDataPinjaman] = useState<[]>([])
    const [formPinjaman, setFormPinjaman] = useState(false)
    const [formAngsuran, setFormAngsuran] = useState(false)

    const { data, setData } = useForm()

    const getDataTabungan = async () => {
        setLoadingDataTabungan(true)
        try {
            const response = await axios.post(route('transaksi.setoran.data-tabungan'), {id: data.anggota})
            setDataTabungan(response.data)
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoadingDataTabungan(false)
        }
    }
    const getDataPinjaman = async () => {
        setLoadingDataPinjaman(true)
        try {
            const response = await axios.post(route('transaksi.setoran.data-pinjaman'), {id: data.anggota})
            setDataPinjaman(response.data)
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoadingDataPinjaman(false)
        }
    }
    const handleFilter = (e: React.FormEvent) => {
        e.preventDefault()
        getDataTabungan()
        getDataPinjaman()
    }
    const handleSetoranBaru = (e:number) => {
        setData((prev: any) => ({ ...prev, jenisTabungan: e }))
        setFormSetoran(true)
    }
    const handleAngsuran = (e:number) => {
        setData((prev: any) => ({ ...prev, pinjaman: e }))
        setFormAngsuran(true)
    }
    
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Filters
                            processing={loadingDataPinjaman && loadingDataTabungan}
                            handleForm={handleFilter}
                            data={data}
                            setData={setData}
                        />
                        <hr />
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            {dataTabungan && dataTabungan.length > 0 ? (
                                <>
                                    {dataTabungan.map((value: any) => (
                                        <Card key={value.id} className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-xl cursor-pointer transition-all duration-300 transform hover:-translate-y-1"
                                        onClick={() => gate.update && handleSetoranBaru(value.jenis_tabungan)}
                                        >
                                            <CardHeader>
                                                <CardTitle className="text-lg text-gray-800 dark:text-gray-100">
                                                {value.tabungan}
                                                </CardTitle>
                                            </CardHeader>
                                            <CardContent>
                                                <p className="text-2xl font-bold text-green-600 dark:text-green-500">
                                                {value.nominal}
                                                </p>
                                                <div className="flex justify-between items-center">
                                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Saldo tersedia</p>
                                                </div>
                                                <div className="flex justify-between items-center">
                                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Tanggal Setoran Terakhir</p>
                                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">{value.last_transaction_date}</p>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ))}
                                    {gate.create && 
                                        <Card className="flex flex-col justify-center items-center h-full bg-white dark:bg-gray-900 border-2 border-dashed border-green-300 dark:border-green-500 rounded-2xl hover:shadow-lg cursor-pointer"
                                            onClick={() => setFormTabungan(true)}
                                        >
                                            <div className="text-center">
                                                <PlusCircle className="mx-auto h-10 w-10 text-green-600 dark:text-green-600" />
                                                <p className="mt-2 text-sm font-medium text-green-600 dark:text-green-500">TABUNGAN</p>
                                            </div>
                                        </Card>
                                    }
                                </>
                            ):null}
                        </div>
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                            { dataPinjaman && dataPinjaman.length > 0 ? (
                                <>
                                    {dataPinjaman.map((value: any) => (
                                        <Card
                                            key={value.id}
                                            className="flex flex-col justify-between h-full min-h-[200px] bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-xl transition-transform duration-300 hover:-translate-y-1 cursor-pointer"
                                            onClick={() => gate.update && handleAngsuran(value.id)}
                                        >
                                            <CardHeader className="pb-2 border-b border-gray-100 dark:border-gray-700">
                                                <CardTitle className="text-base font-semibold text-gray-700 dark:text-gray-200 tracking-wide uppercase">
                                                    Pinjaman
                                                </CardTitle>
                                            </CardHeader>
                                            <CardContent className="space-y-4 pt-3 text-sm text-gray-600 dark:text-gray-400">
                                                <div className="grid grid-cols-2 gap-4">
                                                    <div className="flex flex-col space-y-1">
                                                        <span className="text-xs text-gray-500 dark:text-gray-400">Nominal</span>
                                                        <span className="text-xl font-bold text-red-600 dark:text-red-500">
                                                            {value.nominal}
                                                        </span>
                                                    </div>
                                                    <div className="flex flex-col space-y-1">
                                                        <span className="text-xs text-gray-500 dark:text-gray-400">Angsuran</span>
                                                        <span className="text-xl font-bold text-green-600 dark:text-green-400">
                                                            {value.angsuran}
                                                        </span>
                                                    </div>
                                                    <div className="flex flex-col space-y-1">
                                                        <span className="text-xs text-gray-500 dark:text-gray-400">Kekurangan</span>
                                                        <span className="text-xl font-bold text-red-600 dark:text-red-500">
                                                            {value.kekurangan}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div className="border-t border-gray-100 dark:border-gray-700 pt-3 space-y-1">
                                                    <div className="flex justify-between items-center">
                                                        <span className="text-sm">Jangka Waktu</span>
                                                        <span className="font-medium">{value.jangka_waktu}</span>
                                                    </div>
                                                    <div className="flex justify-between items-center">
                                                        <span className="text-sm">Jumlah Angsuran</span>
                                                        <span className="font-medium">{value.jumlah_angsuran}</span>
                                                    </div>
                                                    <div className="flex justify-between items-center">
                                                        <span className="text-sm">Tanggal Angsuran Terakhir</span>
                                                        <span className="font-medium">{value.last_transaction_date}</span>
                                                    </div>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ))}
                                    {gate.create && (
                                        <Card
                                            className="flex flex-col justify-center items-center h-full min-h-[200px] bg-white dark:bg-gray-900 border-2 border-dashed border-red-300 dark:border-red-500 rounded-2xl hover:shadow-lg cursor-pointer"
                                            onClick={() => setFormPinjaman(true)}
                                        >
                                            <div className="text-center">
                                                <PlusCircle className="mx-auto h-10 w-10 text-red-600 dark:text-red-500" />
                                                <p className="mt-2 text-sm font-medium text-red-600 dark:text-red-500">PINJAMAN</p>
                                            </div>
                                        </Card>
                                    )}
                                </>
                            ) : (
                                data.anggota && gate.create && (
                                    <Card
                                        className="flex flex-col justify-center items-center h-full min-h-[200px] bg-white dark:bg-gray-900 border-2 border-dashed border-red-300 dark:border-red-500 rounded-2xl hover:shadow-lg cursor-pointer"
                                        onClick={() => setFormPinjaman(true)}
                                    >
                                        <div className="text-center">
                                            <PlusCircle className="mx-auto h-10 w-10 text-red-600 dark:text-red-500" />
                                            <p className="mt-2 text-sm font-medium text-red-600 dark:text-red-500">PINJAMAN</p>
                                        </div>
                                    </Card>
                                )
                            )}
                        </div>
                    </CardContent>
                </Card>
            </div>
            <FormTabunganBaru open={formTabungan} setOpen={setFormTabungan} onChange={getDataTabungan} model={data.anggota}/>
            <FormSetoranBaru open={formSetoran} setOpen={setFormSetoran} onChange={getDataTabungan} model={data}/>
            <FormPinjamanBaru open={formPinjaman} setOpen={setFormPinjaman} onChange={getDataPinjaman} model={data.anggota}/>
            <FormAngsuran open={formAngsuran} setOpen={setFormAngsuran} onChange={getDataPinjaman} model={data}/>
        </AppLayout>
    )
}

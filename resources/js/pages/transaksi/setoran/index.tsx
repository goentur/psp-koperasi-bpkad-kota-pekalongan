import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, IndexGate } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useState } from 'react'
import Filters from './components/filters'
import { PlusCircle } from 'lucide-react'

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
    const [loading, setLoading] = useState(false)
    const [formTabungan, setFormTabungan] = useState(false)
    const [dataTabungan, setDataTabungan] = useState<[]>([])
    const [dataPinjaman, setDataPinjaman] = useState<[]>([])

    const { data, setData, post, processing } = useForm()

    const handleFilter = async (e: React.FormEvent) => {
        e.preventDefault()
        setLoading(true)
        try {
            const response = await axios.post(route('transaksi.setoran.data'), {id: data.anggota})
            setDataTabungan(response.data.tabungan)
            setDataPinjaman(response.data.pinjaman)
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoading(false)
        }
    }
    const handleClick = async (e:number) => {
        
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
                            processing={loading}
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
                                        onClick={() => gate.update && handleClick(value.id)}
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
                                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Saldo tersedia</p>
                                            </CardContent>
                                        </Card>
                                    ))}
                                    {gate.create && 
                                        <Card className="bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center h-40 transition-colors duration-200 transform hover:bg-gray-50 dark:hover:bg-gray-700"
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
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            {dataPinjaman && dataPinjaman.length > 0 ? (
                                <>
                                    {dataPinjaman.map((value: any) => (
                                        <Card key={value.id} className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-xl cursor-pointer transition-all duration-300 transform hover:-translate-y-1"
                                        onClick={() => gate.update && handleClick(value.id)}
                                        >
                                            <CardHeader>
                                                <CardTitle className="text-lg text-gray-800 dark:text-gray-100">
                                                PINJAMAN
                                                </CardTitle>
                                            </CardHeader>
                                            <CardContent>
                                                <p className="text-2xl font-bold text-red-600 dark:text-red-500">
                                                {value.nominal}
                                                </p>
                                                <p className="text-2xl font-bold text-green-600 dark:text-green-500">
                                                {value.angsuran}
                                                </p>
                                                <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Selama {value.jangka_waktu}</p>
                                            </CardContent>
                                        </Card>
                                    ))}
                                    {gate.create && 
                                        <Card className="bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-600 shadow-sm hover:shadow-md cursor-pointer flex items-center justify-center h-40 transition-colors duration-200 transform hover:bg-gray-50 dark:hover:bg-gray-700"
                                            onClick={() => setFormTabungan(true)}
                                        >
                                            <div className="text-center">
                                                <PlusCircle className="mx-auto h-10 w-10 text-red-600 dark:text-red-600" />
                                                <p className="mt-2 text-sm font-medium text-red-600 dark:text-red-500">PINJAMAN</p>
                                            </div>
                                        </Card>
                                    }
                                </>
                            ):null}
                            </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}

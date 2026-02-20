import FormCalendar from '@/components/form-calendar'
import FormInputCurrency from '@/components/form-input-currency'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { Field, FieldContent, FieldDescription, FieldLabel, FieldTitle } from '@/components/ui/field'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import { alertApp } from '@/components/utils'
import { useForm } from '@inertiajs/react'
import { Loader2, Save } from 'lucide-react'
import { useEffect, useRef } from 'react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    onChange: () => void
    model: any
}& Record<string, any>
export default function FormSetoranBaru({ open, setOpen, onChange, model}: props) {
    const { data, setData, post, reset, errors, clearErrors, processing } = useForm({
        anggota : '',
        jenisTabungan : '',
        tipe : '',
        tanggal : new Date(),
        nominal : ''
    })
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('transaksi.setoran-atau-tarik'), {
            preserveScroll: true,
            onSuccess: (e) => {
                clearErrors()
                reset(),
                alertApp(e)
                onChange?.()
                setOpen(false)
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }

    useEffect(() => {
        if (open && model) {
            setData((prev) => ({ ...prev, anggota: model.anggota, jenisTabungan: model.jenisTabungan, tipe : 'setoran', nominal:model.minimumStoran }))
        }
    }, [open, model])

    return (
        <Dialog open={open} onOpenChange={(val) => {
            setOpen(val);
            if (!val && onChange) onChange();
        }}>
            <DialogContent className='w-2xl'>
                <form onSubmit={handleForm}>
                    <DialogHeader>
                        <DialogTitle>Form Setoran</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="mt-5 mb-2">
                        
                        <Card className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-xl cursor-pointer transition-all duration-300 transform hover:-translate-y-1 mb-5"
                        >
                            <CardHeader className='text-center'>
                                <CardTitle className="text-lg text-gray-800 dark:text-gray-100">
                                {model.namaAnggota}
                                </CardTitle>
                            </CardHeader>
                        </Card>
                        <Card className="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md hover:shadow-xl cursor-pointer transition-all duration-300 transform hover:-translate-y-1"
                        >
                            <CardHeader>
                                <CardTitle className="text-lg text-gray-800 dark:text-gray-100">
                                {model.namaTabungan}
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p className="text-2xl font-bold text-green-600 dark:text-green-500">
                                {model.nominalTabungan}
                                </p>
                                <div className="flex justify-between items-center">
                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Saldo tersedia</p>
                                </div>
                                <div className="flex justify-between items-center">
                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">Tanggal Setoran Terakhir</p>
                                    <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">{model.tanggalTerakhir}</p>
                                </div>
                            </CardContent>
                        </Card>
                        <hr className='my-5' />
                        <RadioGroup 
                            defaultValue="setoran" 
                            value={data.tipe}
                            onValueChange={(value) => setData((prev: any) => ({ ...prev, tipe: value }))}
                            className="flex flex-row gap-4"
                        >
                            <FieldLabel 
                                htmlFor="setoran" 
                                className={`
                                    flex-1 cursor-pointer rounded-lg border-2 transition-all
                                    ${data.tipe === 'setoran' 
                                        ? 'bg-green-50 border-green-500 ring-1 ring-green-500' 
                                        : ''}
                                `}
                            >
                                <Field orientation="horizontal">
                                    <FieldContent>
                                        <FieldTitle className={data.tipe === 'setoran' ? 'text-green-600' : ''}>
                                            Setoran
                                        </FieldTitle>
                                        <FieldDescription className={data.tipe === 'setoran' ? 'text-green-600' : ''}>
                                            Dipilih untuk setoran.
                                        </FieldDescription>
                                    </FieldContent>
                                    <RadioGroupItem 
                                        value="setoran" 
                                        id="setoran"
                                    />
                                </Field>
                            </FieldLabel>
                            <FieldLabel 
                                htmlFor="tarik" 
                                className={`
                                    flex-1 cursor-pointer rounded-lg border-2 transition-all
                                    ${data.tipe === 'tarik' 
                                        ? 'bg-red-50 border-red-500 ring-1 ring-red-500' 
                                        : ''}
                                `}
                            >
                                <Field orientation="horizontal">
                                    <FieldContent>
                                        <FieldTitle className={data.tipe === 'tarik' ? 'text-red-600' : ''}>
                                            Tarik
                                        </FieldTitle>
                                        <FieldDescription className={data.tipe === 'tarik' ? 'text-red-600' : ''}>
                                            Dipilih untuk tarik.
                                        </FieldDescription>
                                    </FieldContent>
                                    <RadioGroupItem 
                                        value="tarik" 
                                        id="tarik"
                                    />
                                </Field>
                            </FieldLabel>
                        </RadioGroup>
                        <div className="grid grid-cols-2 gap-2 mt-2">
                            <FormCalendar
                                id="tanggal"
                                value={data.tanggal}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggal: e }))}
                                inputRef={(el) => {if (formRefs.current) {formRefs.current['tanggal'] = el}}}
                                placeholder="Pilih tanggal pembayaran"
                                error={errors.tanggal}
                                required
                                autoComplete='off'
                                tanggalSelanjutnya={false}
                            />
                            <FormInputCurrency
                                id="nominal"
                                type="text"
                                value={data.nominal}
                                onChange={(value, floatValue) => setData((prevData: any) => ({ ...prevData, nominal: floatValue }))}
                                inputRef={(el) => {if (formRefs.current) {formRefs.current['nominal'] = el}}}
                                placeholder="Masukkan nominal"
                                error={errors.nominal}
                                autoComplete='off'
                                required
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <div className="flex items-center mt-5">
                            <Button type="submit" disabled={processing}> {processing ? <Loader2 className="animate-spin" /> : <Save /> } Simpan</Button>
                        </div>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    )
}

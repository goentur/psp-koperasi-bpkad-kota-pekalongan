import ComboboxDinamis from '@/components/combobox-dinamis'
import FormCalendar from '@/components/form-calendar'
import FormInputCurrency from '@/components/form-input-currency'
import { Button } from '@/components/ui/button'
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle
} from '@/components/ui/dialog'
import { alertApp } from '@/components/utils'
import { useForm } from '@inertiajs/react'
import axios from 'axios'
import { Loader2, Save } from 'lucide-react'
import { useEffect, useRef, useState } from 'react'
type props = {
    open: boolean
    setOpen: (open: boolean) => void
    onChange: () => void
    model: any
}& Record<string, any>
export default function FormTabunganBaru({ open, setOpen, onChange, model}: props) {
    const { data, setData, post, reset, errors, clearErrors, processing } = useForm({
        anggota : '',
        jenisTabungan : '',
        tanggalPendaftaran : new Date(),
        nominal : ''
    })
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('transaksi.tabungan-baru'), {
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

    const [jenisTabungan, setJenisTabungan] = useState("");
    useEffect(() => {
        setJenisTabungan("")
        if (open && model) {
            setData((prev) => ({ ...prev, anggota: model }))
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
                        <DialogTitle>Form Tabungan Baru</DialogTitle>
                        <DialogDescription className='italic'>"Silakan isi formulir di bawah ini dengan lengkap dan benar"</DialogDescription>
                    </DialogHeader>
                    <div className="mt-5 mb-2">
                        <ComboboxDinamis
                            label="jenisTabungan"
                            selectedValue={data.jenisTabungan}
                            fetchOptions={async (search) => {
                                const { data: options } = await axios.post(route('jenis-tabungan.list'), {id: data.jenisTabungan,search});
                                if (!options.find((d: any) => d.value === data.jenisTabungan) && search !== '') {
                                    setData((prev: any) => ({ ...prev, jenisTabungan: '' }));
                                }
                                return options;
                            }}
                            onSelect={(value, label) => {setData((prev: any) => ({ ...prev, jenisTabungan: value })); setJenisTabungan(label)}}
                            search={jenisTabungan}
                            setSearch={setJenisTabungan}
                            error={errors.jenisTabungan}
                            className="mt-2"
                        />
                        <div className="grid grid-cols-2 gap-2 mt-2">
                            <FormCalendar
                                id="tanggalPendaftaran"
                                value={data.tanggalPendaftaran}
                                onChange={(e) => setData((prevData: any) => ({ ...prevData, tanggalPendaftaran: e }))}
                                inputRef={(el) => {if (formRefs.current) {formRefs.current['tanggalPendaftaran'] = el}}}
                                placeholder="Pilih tanggal pendaftaran"
                                error={errors.tanggalPendaftaran}
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
